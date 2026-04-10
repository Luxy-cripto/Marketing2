<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Produk;
use App\Models\FollowUp;
use App\Models\DetailTransaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransaksiExport;
use App\Exports\ProdukTerlarisExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ===============================
    // AUTO SYNC STATUS KONSUMEN
    // ===============================
    private function syncStatusKonsumen($konsumen_id)
    {
        $konsumen = Konsumen::with('transaksis')->find($konsumen_id);
        if (!$konsumen) return;

        $adaTransaksi = $konsumen->transaksis()->exists();
        $konsumen->status = $adaTransaksi ? 'Deal' : 'Prospek';
        $konsumen->save();
    }

    // ===============================
    // EXPORT EXCEL
    // ===============================
    public function exportTransaksi(Request $request)
    {
        return Excel::download(
            new TransaksiExport(
                $request->search,
                $request->produk_id,
                $request->start_date,
                $request->end_date
            ),
            'laporan_transaksi.xlsx'
        );
    }

    public function exportProdukTerlaris(Request $request)
    {
        return Excel::download(
            new ProdukTerlarisExport($request->tanggal),
            'laporan_produk_terlaris.xlsx'
        );
    }

    // ===============================
    // INVOICE PDF
    // ===============================
    public function invoice($id)
    {
        $transaksi = Transaksi::with(['konsumen', 'details.produk'])->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.invoice', compact('transaksi'));
        return $pdf->download('invoice-' . $transaksi->id . '.pdf');
    }

    // ===============================
    // LIST DATA
    // ===============================
    public function index(Request $request)
    {
        $query = Transaksi::with(['konsumen', 'details.produk']);

        if ($request->search) {
            $query->whereHas('konsumen', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_transaksi', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->latest()->paginate(10);

        $totalOmzet = (clone $query)->where('status', 'Lunas')->sum('total');

        $totalProduk = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.status', 'Lunas')
            ->sum('detail_transaksis.qty');

        $produkTerlaris = DB::table('detail_transaksis')
            ->join('produks', 'detail_transaksis.produk_id', '=', 'produks.id')
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.status', 'Lunas')
            ->select(
                'produks.nama',
                DB::raw('SUM(detail_transaksis.qty) as total_qty'),
                DB::raw('SUM(detail_transaksis.subtotal) as total_omzet')
            )
            ->groupBy('produks.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('transaksi.index', compact(
            'transaksis',
            'totalOmzet',
            'totalProduk',
            'produkTerlaris'
        ));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        return view('transaksi.create', [
            'konsumens' => Konsumen::all(),
            'produks' => Produk::all()
        ]);
    }

    // ===============================
    // STORE (🔥 FINAL)
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'qty' => 'required|array',
            'qty.*' => 'integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:Belum Bayar,Lunas'
        ]);

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::create([
                'konsumen_id' => $request->konsumen_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'status' => $request->status,
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->produk_id as $i => $produk_id) {
                $produk = Produk::findOrFail($produk_id);
                $qty = $request->qty[$i];

                if ($produk->stok < $qty) {
                    throw new \Exception("Stok {$produk->nama} tidak cukup!");
                }

                $subtotal = $qty * $produk->harga;

                DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $produk_id,
                'qty' => $qty,
                'harga' => $produk->harga, // 🔥 WAJIB TAMBAH
                'harga_satuan' => $produk->harga,
                'subtotal' => $subtotal
            ]);

                $produk->stok -= $qty;
                $produk->save();

                $total += $subtotal;
            }

            $transaksi->update(['total' => $total]);

            // 🔥 AUTO FOLLOW UP (ANTI DOUBLE)
            FollowUp::updateOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'konsumen_id' => $transaksi->konsumen_id,
                    'user_id' => Auth::id(),
                    'status' => 'Belum Dihubungi',
                    'catatan' => 'Follow-up otomatis',
                    'follow_up_date' => now()
                ]
            );

            $this->syncStatusKonsumen($request->konsumen_id);

            DB::commit();

            return view('transaksi.success', compact('transaksi'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ===============================
    // SHOW
    // ===============================
    public function show($id)
    {
        $transaksi = Transaksi::with([
            'konsumen',
            'details.produk',
            'followUps.user'
        ])->findOrFail($id);

        return view('transaksi.show', compact('transaksi'));
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();

        try {
            foreach ($transaksi->details as $d) {
                $produk = $d->produk;
                $produk->stok += $d->qty;
                $produk->save();
            }

            $konsumen_id = $transaksi->konsumen_id;
            $transaksi->delete();

            $this->syncStatusKonsumen($konsumen_id);

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);

        return view('transaksi.edit', [
            'transaksi' => $transaksi,
            'konsumens' => Konsumen::all(),
            'produks' => Produk::all()
        ]);
    }

    // ===============================
    // UPDATE (🔥 FIX SYNC)
    // ===============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'qty' => 'required|array',
            'qty.*' => 'integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:Belum Bayar,Lunas'
        ]);

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::with('details')->findOrFail($id);

            // balik stok
            foreach ($transaksi->details as $d) {
                $d->produk->increment('stok', $d->qty);
            }

            // hapus detail lama
            DetailTransaksi::where('transaksi_id', $transaksi->id)->delete();

            $total = 0;

            foreach ($request->produk_id as $i => $produk_id) {
                $produk = Produk::findOrFail($produk_id);
                $qty = $request->qty[$i];

                if ($produk->stok < $qty) {
                    throw new \Exception("Stok {$produk->nama} tidak cukup!");
                }

                $subtotal = $qty * $produk->harga;

               DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $produk_id,
                'qty' => $qty,
                'harga' => $produk->harga, // 🔥 WAJIB TAMBAH
                'harga_satuan' => $produk->harga,
                'subtotal' => $subtotal
            ]);

                $produk->decrement('stok', $qty);
                $total += $subtotal;
            }

            $transaksi->update([
                'konsumen_id' => $request->konsumen_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'status' => $request->status,
                'total' => $total
            ]);

            // 🔥 SYNC FOLLOW UP
            FollowUp::updateOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'konsumen_id' => $request->konsumen_id
                ]
            );

            DB::commit();

            return redirect()->route('transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
