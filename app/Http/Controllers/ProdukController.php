<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // ===============================
    // LIST PRODUK
    // ===============================
    public function index()
    {
        $produks = Produk::latest()->get();

        return view('produk.index', compact('produks'));
    }

    // ===============================
    // FORM TAMBAH
    // ===============================
    public function create()
    {
        return view('produk.create');
    }

    // ===============================
    // SIMPAN PRODUK
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Produk::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => str_replace('.', '', $request->harga),
            'stok' => $request->stok,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // ===============================
    // FORM EDIT
    // ===============================
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    // ===============================
    // UPDATE PRODUK
    // ===============================
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        $produk->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => str_replace('.', '', $request->harga),
            'stok' => $request->stok,
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    // ===============================
    // HAPUS PRODUK
    // ===============================
    public function destroy(Produk $produk)
    {
        if (method_exists($produk, 'konsumens')) {
            $produk->konsumens()->detach();
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    // ===============================
    // DETAIL PRODUK
    // ===============================
    public function show(Produk $produk)
    {
        $produk->load('konsumens');

        return view('produk.show', compact('produk'));
    }
}
