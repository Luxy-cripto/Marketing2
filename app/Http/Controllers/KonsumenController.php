<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// EXPORT
use App\Exports\KonsumenExport;
use Maatwebsite\Excel\Facades\Excel;

// IMPORT
use App\Imports\KonsumenImport;

class KonsumenController extends Controller
{
    // ==============================
    // LIST DATA KONSUMEN
    // ==============================
    public function index(Request $request)
    {
        $query = Konsumen::with(['user','produks']); // 🔥 tambah produks

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama','like','%'.$request->search.'%')
                  ->orWhere('no_hp','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
            });
        }

        $konsumens = $query->latest()->paginate(10)->withQueryString();

        return view('konsumen.index', compact('konsumens'));
    }

    // ==============================
    // FORM TAMBAH
    // ==============================
    public function create()
    {
        $produks = Produk::all(); // 🔥 ambil produk

        return view('konsumen.create', compact('produks'));
    }

    // ==============================
    // SIMPAN DATA
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'sumber_lead' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'nullable|in:Prospek,Deal,Tidak Tertarik',

            // 🔥 MULTI PRODUK
            'produk_id' => 'nullable|array',
            'produk_id.*' => 'exists:produks,id',
        ]);

        $konsumen = Konsumen::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'sumber_lead' => $request->sumber_lead,
            'status' => $request->status ?? 'Prospek',
            'user_id' => Auth::id(),
        ]);

        // 🔥 SIMPAN RELASI PRODUK
        if ($request->produk_id) {
            $konsumen->produks()->sync($request->produk_id);
        }

        return redirect()->route('konsumen.index')
            ->with('success','Data konsumen berhasil ditambahkan');
    }

    // ==============================
    // FORM EDIT
    // ==============================
    public function edit(Konsumen $konsumen)
    {
        $user = Auth::user();

        if ($user->role === 'marketing' && $konsumen->user_id !== $user->id) {
            abort(403,'Tidak punya akses');
        }

        $produks = Produk::all(); // 🔥 ambil produk

        return view('konsumen.edit', compact('konsumen','produks'));
    }

    // ==============================
    // UPDATE DATA
    // ==============================
    public function update(Request $request, Konsumen $konsumen)
    {
        $user = Auth::user();

        if ($user->role === 'marketing' && $konsumen->user_id !== $user->id) {
            abort(403,'Tidak punya akses');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'sumber_lead' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'status' => 'nullable|in:Prospek,Deal,Tidak Tertarik',

            // 🔥 MULTI PRODUK
            'produk_id' => 'nullable|array',
            'produk_id.*' => 'exists:produks,id',
        ]);

        $konsumen->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'sumber_lead' => $request->sumber_lead,
            'status' => $request->status ?? $konsumen->status,
        ]);

        // 🔥 UPDATE RELASI PRODUK
        $konsumen->produks()->sync($request->produk_id ?? []);

        return redirect()->route('konsumen.index')
            ->with('success','Data konsumen berhasil diupdate');
    }

    // ==============================
    // HAPUS DATA
    // ==============================
    public function destroy(Konsumen $konsumen)
    {
        $user = Auth::user();

        if ($user->role === 'marketing' && $konsumen->user_id !== $user->id) {
            abort(403,'Tidak punya akses');
        }

        // 🔥 hapus relasi dulu
        $konsumen->produks()->detach();

        $konsumen->delete();

        return redirect()->route('konsumen.index')
            ->with('success','Data berhasil dihapus');
    }

    // ==============================
    // LIVE SEARCH AJAX
    // ==============================
    public function liveSearch(Request $request)
    {
        $query = Konsumen::with(['user','produks']);

        if ($request->search) {
            $query->where(function($q) use ($request){
                $q->where('nama','like','%'.$request->search.'%')
                  ->orWhere('no_hp','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
            });
        }

        if ($request->status) {
            $query->where('status',$request->status);
        }

        $konsumens = $query->latest()->get();

        return response()->json($konsumens);
    }

    // ==============================
    // IMPORT EXCEL
    // ==============================
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new KonsumenImport, $request->file('file'));

        return redirect()->route('konsumen.index')
            ->with('success','Data konsumen berhasil diimport');
    }

    // ==============================
    // EXPORT EXCEL
    // ==============================
    public function export(Request $request)
    {
        $status = $request->status;

        $fileName = 'data_konsumen.xlsx';

        if($status){
            $fileName = 'data_konsumen_'.$status.'.xlsx';
        }

        return Excel::download(new KonsumenExport($status), $fileName);
    }

    // ==============================
    // DETAIL DATA
    // ==============================
    public function show(Konsumen $konsumen)
    {
        $konsumen->load('produks'); // 🔥 tampilkan produk

        return view('konsumen.show', compact('konsumen'));
    }
}
