<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $produks = Produk::latest()->get();
        return view('produk.index', compact('produks'));
    }

    // Form tambah produk
    public function create()
    {
        return view('produk.create');
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Form edit produk
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    // Update produk
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    // Hapus produk
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
