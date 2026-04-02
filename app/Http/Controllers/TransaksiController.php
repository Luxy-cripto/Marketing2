<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Produk;
use App\Models\FollowUp;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransaksiExport;
use App\Exports\ProdukTerlarisExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // ===============================
    // 🔥 AUTO SYNC STATUS KONSUMEN
    // ===============================
    private function syncStatusKonsumen($konsumen_id)
    {
        $konsumen = Konsumen::with('transaksis')->find($konsumen_id);

        if (!$konsumen) return;

        // 🔥 LOGIKA BARU: ADA TRANSAKSI = DEAL
        $adaTransaksi = $konsumen->transaksis()->exists();

        $konsumen->status = $adaTransaksi ? 'Deal' : 'Prospek';
        $konsumen->save();
    }

    // ===============================
    // EXPORT TRANSAKSI
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

    // ===============================
    // EXPORT PRODUK TERLARIS
    // ===============================
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
        $transaksi = Transaksi::with(['konsumen','produk'])->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.invoice', compact('transaksi'));
        return $pdf->download('invoice-'.$transaksi->id.'.pdf');
    }

    // ===============================
    // LIST
    // ===============================
    public function index(Request $request)
    {
        $query = Transaksi::with(['konsumen','produk']);

        if ($request->search) {
            $query->where(function($q) use ($request){
                $q->whereHas('konsumen', fn($q2) =>
                    $q2->where('nama','like','%'.$request->search.'%'))
                ->orWhereHas('produk', fn($q2) =>
                    $q2->where('nama','like','%'.$request->search.'%'));
            });
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_transaksi', $request->tanggal);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->latest()->paginate(10);

        $totalOmzet = (clone $query)
            ->where('status','Lunas')
            ->sum('total');

        $totalProduk = (clone $query)
            ->where('status','Lunas')
            ->sum('qty');

        $produkTerlaris = DB::table('transaksis')
            ->join('produks','transaksis.produk_id','=','produks.id')
            ->where('transaksis.status','Lunas')
            ->select(
                'produks.nama',
                DB::raw('SUM(transaksis.qty) as total_qty'),
                DB::raw('SUM(transaksis.total) as total_omzet')
            )
            ->groupBy('produks.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('transaksi.index', compact(
            'transaksis','totalOmzet','totalProduk','produkTerlaris'
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
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:Belum Bayar,Lunas'
        ]);

        DB::beginTransaction();

        try {
            $produk = Produk::findOrFail($request->produk_id);

            if ($produk->stok < $request->qty) {
                return back()->with('error','Stok tidak cukup!');
            }

            $transaksi = Transaksi::create([
                'konsumen_id' => $request->konsumen_id,
                'produk_id' => $request->produk_id,
                'qty' => $request->qty,
                'harga_satuan' => $produk->harga,
                'total' => $produk->harga * $request->qty,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'status' => $request->status
            ]);

            // 🔥 AUTO DEAL LANGSUNG
            $this->syncStatusKonsumen($request->konsumen_id);

            $produk->stok -= $request->qty;
            $produk->save();

            FollowUp::create([
                'konsumen_id' => $transaksi->konsumen_id,
                'user_id' => Auth::id(),
                'status' => 'Belum Dihubungi',
                'catatan' => 'Follow-up otomatis dari transaksi #' . $transaksi->id,
                'follow_up_date' => now()
            ]);

            DB::commit();

            return redirect()->route('transaksi.success', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error','Error: '.$e->getMessage());
        }
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit(Transaksi $transaksi)
    {
        return view('transaksi.edit', [
            'transaksi' => $transaksi,
            'konsumens' => Konsumen::all(),
            'produks' => Produk::all()
        ]);
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:Belum Bayar,Lunas'
        ]);

        DB::beginTransaction();

        try {
            $produkLama = $transaksi->produk;
            $produkBaru = Produk::findOrFail($request->produk_id);

            $produkLama->stok += $transaksi->qty;
            $produkLama->save();

            if ($produkBaru->stok < $request->qty) {
                throw new \Exception('Stok tidak cukup!');
            }

            $transaksi->update([
                'konsumen_id' => $request->konsumen_id,
                'produk_id' => $request->produk_id,
                'qty' => $request->qty,
                'harga_satuan' => $produkBaru->harga,
                'total' => $produkBaru->harga * $request->qty,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'status' => $request->status
            ]);

            $produkBaru->stok -= $request->qty;
            $produkBaru->save();

            // 🔥 AUTO DEAL
            $this->syncStatusKonsumen($request->konsumen_id);

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success','Transaksi berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error',$e->getMessage());
        }
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();

        try {
            $produk = $transaksi->produk;

            $produk->stok += $transaksi->qty;
            $produk->save();

            $konsumen_id = $transaksi->konsumen_id;

            $transaksi->delete();

            // 🔥 UPDATE STATUS
            $this->syncStatusKonsumen($konsumen_id);

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success','Transaksi dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error',$e->getMessage());
        }
    }

    // ===============================
    // BAYAR
    // ===============================
    public function bayar(Transaksi $transaksi)
    {
        DB::beginTransaction();

        try {
            $transaksi->status = 'Lunas';
            $transaksi->save();

            // 🔥 TETAP DEAL (SUDAH PASTI)
            $this->syncStatusKonsumen($transaksi->konsumen_id);

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success','Transaksi berhasil dibayar');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error',$e->getMessage());
        }
    }

    // ===============================
    // DETAIL
    // ===============================
    public function show($id)
    {
        $transaksi = Transaksi::with('konsumen', 'produk')->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }
}
