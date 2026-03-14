<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Produk;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Daftar transaksi
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen','produk'])->latest()->get();

        // Produk terlaris (5 produk terbanyak terjual)
        $produk_terlaris = Transaksi::selectRaw('produk_id, SUM(qty) as total_qty')
            ->groupBy('produk_id')
            ->with('produk')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('transaksi.index', compact('transaksis','produk_terlaris'));
    }

    // Form tambah transaksi
    public function create()
    {
        $konsumens = Konsumen::all();
        $produks = Produk::all();
        return view('transaksi.create', compact('konsumens','produks'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id'=>'required|exists:konsumens,id',
            'produk_id'=>'required|exists:produks,id',
            'qty'=>'required|integer|min:1'
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->qty) {
            return back()->with('error','Stok produk tidak cukup!');
        }

        // Buat transaksi
        $transaksi = Transaksi::create([
    'konsumen_id'=>$request->konsumen_id,
    'produk_id'=>$request->produk_id,
    'qty'=>$request->qty,
    'harga_satuan'=>$produk->harga,
    'total'=>$produk->harga * $request->qty
]);

// Ubah status konsumen jadi Deal
$konsumen = Konsumen::find($request->konsumen_id);
$konsumen->status = 'Deal';
$konsumen->save();

// Kurangi stok produk
$produk->stok -= $request->qty;
$produk->save();

// Follow-Up otomatis
FollowUp::create([
    'konsumen_id' => $transaksi->konsumen_id,
    'user_id' => Auth::id(),
    'status' => 'Belum Dihubungi',
    'catatan' => 'Follow-up otomatis dari transaksi #' . $transaksi->id,
    'follow_up_date' => now()
]);

        return redirect()->route('transaksi.index')->with('success','Transaksi berhasil disimpan!');
    }

    // Form edit transaksi
    public function edit(Transaksi $transaksi)
    {
        $konsumens = Konsumen::all();
        $produks = Produk::all();
        return view('transaksi.edit', compact('transaksi','konsumens','produks'));
    }

    // Update transaksi
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'konsumen_id'=>'required|exists:konsumens,id',
            'produk_id'=>'required|exists:produks,id',
            'qty'=>'required|integer|min:1'
        ]);

        $produkLama = $transaksi->produk;
        $produkBaru = Produk::findOrFail($request->produk_id);

        // Kembalikan stok produk lama
        $produkLama->stok += $transaksi->qty;
        $produkLama->save();

        // Cek stok produk baru cukup
        if ($produkBaru->stok < $request->qty) {
            // rollback stok lama
            $produkLama->stok -= $transaksi->qty;
            $produkLama->save();
            return back()->with('error','Stok produk baru tidak cukup!');
        }

        // Update transaksi
        $transaksi->update([
            'konsumen_id'=>$request->konsumen_id,
            'produk_id'=>$request->produk_id,
            'qty'=>$request->qty,
            'harga_satuan'=>$produkBaru->harga,
            'total'=>$produkBaru->harga * $request->qty
        ]);

        // Kurangi stok produk baru
        $produkBaru->stok -= $request->qty;
        $produkBaru->save();

        return redirect()->route('transaksi.index')
            ->with('success','Transaksi berhasil diupdate dan stok diperbarui!');
    }

    // Hapus transaksi
    public function destroy(Transaksi $transaksi)
    {
        $produk = $transaksi->produk;

        // Kembalikan stok
        $produk->stok += $transaksi->qty;
        $produk->save();

        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success','Transaksi berhasil dihapus dan stok dikembalikan!');
    }
}
