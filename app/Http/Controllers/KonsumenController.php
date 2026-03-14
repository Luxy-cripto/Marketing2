<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsumenController extends Controller
{
    // Tampilkan daftar konsumen
    public function index(Request $request)
    {
        $query = Konsumen::with('user');

        // FILTER STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // SEARCH (nama, no_hp, email)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('no_hp', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // ROLE BASED ACCESS
        $user = Auth::user();
        if ($user->role === 'marketing') {
            // Marketing bisa lihat semua konsumen
            // tapi nanti di form edit / update dicek untuk hak akses
        }

        $konsumens = $query->latest()->paginate(10)->withQueryString();

        return view('konsumen.index', compact('konsumens'));
    }

    // Form tambah konsumen
    public function create()
    {
        return view('konsumen.create');
    }

    // Simpan konsumen baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'sumber_lead' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'nullable|in:Prospek,Deal,Tidak Tertarik',
        ]);

        Konsumen::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'sumber_lead' => $request->sumber_lead,
            'status' => $request->status ?? 'Prospek',
            'user_id' => Auth::id(), // yang buat data
        ]);

        return redirect()->route('konsumen.index')
            ->with('success', 'Data konsumen berhasil ditambahkan');
    }

    // Form edit konsumen
    public function edit(Konsumen $konsumen)
    {
        $user = Auth::user();

        // Cek hak akses: marketing tidak bisa edit konsumen admin
        if ($user->role === 'marketing' && $konsumen->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit konsumen ini.');
        }

        return view('konsumen.edit', compact('konsumen'));
    }

    // Update data konsumen
    public function update(Request $request, Konsumen $konsumen)
    {
        $user = Auth::user();

        // Cek hak akses: marketing tidak bisa update konsumen admin
        if ($user->role === 'marketing' && $konsumen->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate konsumen ini.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'sumber_lead' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'nullable|in:Prospek,Deal,Tidak Tertarik',
        ]);

        $konsumen->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'sumber_lead' => $request->sumber_lead,
            'status' => $request->status ?? $konsumen->status,
        ]);

        return redirect()->route('konsumen.index')
            ->with('success', 'Data konsumen berhasil diupdate');
    }

    // Hapus konsumen
    public function destroy(Konsumen $konsumen)
    {
        $user = Auth::user();

        // Marketing tidak bisa hapus konsumen admin
        if ($user->role === 'marketing' && $konsumen->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus konsumen ini.');
        }

        $konsumen->delete();

        return redirect()->route('konsumen.index')
            ->with('success', 'Data konsumen berhasil dihapus');
    }

    // Live search untuk AJAX
    public function liveSearch(Request $request)
    {
        $query = Konsumen::with('user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('no_hp', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $konsumens = $query->latest()->get();

        return response()->json($konsumens);
    }

    // Tampilkan detail konsumen
    public function show(Konsumen $konsumen)
    {
        return view('konsumen.show', compact('konsumen'));
    }
}
