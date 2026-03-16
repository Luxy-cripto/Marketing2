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
    // EXPORT TRANSAKSI
    public function exportTransaksi(Request $request)
    {
        $search = $request->search;
        $tanggal = $request->tanggal;

        return Excel::download(
            new TransaksiExport($search, $tanggal),
            'laporan_transaksi.xlsx'
        );
    }

    // EXPORT PRODUK TERLARIS
    public function exportProdukTerlaris(Request $request)
    {
        $tanggal = $request->tanggal;

        return Excel::download(
            new ProdukTerlarisExport($tanggal),
            'laporan_produk_terlaris.xlsx'
        );
    }

    // GENERATE INVOICE PDF
    public function invoice($id)
    {
        $transaksi = Transaksi::with(['konsumen','produk'])->findOrFail($id);
        $pdf = Pdf::loadView('transaksi.invoice', compact('transaksi'));
        return $pdf->download('invoice-transaksi-'.$transaksi->id.'.pdf');
    }

    // HALAMAN DATA TRANSAKSI
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen','produk'])
            ->latest()
            ->get();

        $produkTerlaris = DB::table('transaksis')
            ->join('produks','transaksis.produk_id','=','produks.id')
            ->select(
                'produks.nama',
                DB::raw('SUM(transaksis.qty) as total_qty'),
                DB::raw('SUM(transaksis.total) as total_omzet')
            )
            ->groupBy('produks.nama')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('transaksi.index', compact('transaksis','produkTerlaris'));
    }

    // FORM TAMBAH TRANSAKSI
    public function create()
    {
        $konsumens = Konsumen::all();
        $produks = Produk::all();

        return view('transaksi.create', compact('konsumens','produks'));
    }

    // SIMPAN TRANSAKSI
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:Belum Bayar,Sudah Bayar'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if($produk->stok < $request->qty){
            return back()->with('error','Stok produk tidak cukup!');
        }

        // Buat transaksi
        $transaksi = Transaksi::create([
            'konsumen_id' => $request->konsumen_id,
            'produk_id' => $request->produk_id,
            'qty' => $request->qty,
            'harga_satuan' => $produk->harga,
            'total' => $produk->harga * $request->qty,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'status' => $request->status
        ]);

        // Set status konsumen jadi Deal
        $konsumen = Konsumen::find($request->konsumen_id);
        $konsumen->status = 'Deal';
        $konsumen->save();

        // Kurangi stok produk
        $produk->stok -= $request->qty;
        $produk->save();

        // Follow up otomatis
        FollowUp::create([
            'konsumen_id' => $transaksi->konsumen_id,
            'user_id' => Auth::id(),
            'status' => 'Belum Dihubungi',
            'catatan' => 'Follow-up otomatis dari transaksi #' . $transaksi->id,
            'follow_up_date' => now()
        ]);

        // Jika status transaksi = Sudah Bayar, otomatis set di database
        if($transaksi->status === 'Sudah Bayar'){
            $transaksi->status = 'Sudah Bayar';
            $transaksi->save();
        }

        return redirect()->route('transaksi.success', $transaksi->id);
    }

    // DETAIL TRANSAKSI
    public function show(Transaksi $transaksi)
    {
        return view('transaksi.show', compact('transaksi'));
    }

    // FORM EDIT
    public function edit(Transaksi $transaksi)
    {
        $konsumens = Konsumen::all();
        $produks = Produk::all();

        return view('transaksi.edit', compact('transaksi','konsumens','produks'));
    }

    // UPDATE TRANSAKSI
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'status' => 'required|in:Belum Bayar,Sudah Bayar'
        ]);

        $produkLama = $transaksi->produk;
        $produkBaru = Produk::findOrFail($request->produk_id);

        // kembalikan stok lama
        $produkLama->stok += $transaksi->qty;
        $produkLama->save();

        if($produkBaru->stok < $request->qty){
            $produkLama->stok -= $transaksi->qty;
            $produkLama->save();
            return back()->with('error','Stok produk baru tidak cukup!');
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

        return redirect()->route('transaksi.index')
            ->with('success','Transaksi berhasil diupdate dan stok diperbarui!');
    }

    // HAPUS TRANSAKSI
    public function destroy(Transaksi $transaksi)
    {
        $produk = $transaksi->produk;

        $produk->stok += $transaksi->qty;
        $produk->save();

        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success','Transaksi berhasil dihapus dan stok dikembalikan!');
    }

    // ===============================
    // BAYAR TRANSAKSI (otomatis set status)
    // ===============================
    public function bayar(Transaksi $transaksi)
    {
        $transaksi->status = 'Sudah Bayar';
        $transaksi->save();

        // Pastikan konsumen tetap Deal
        $konsumen = $transaksi->konsumen;
        $konsumen->status = 'Deal';
        $konsumen->save();

        return redirect()->route('transaksi.index')
            ->with('success','Transaksi berhasil dibayar dan status diperbarui!');
    }
}
