<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Konsumen;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    // =========================
    // LIST DATA
    // =========================
    public function index()
    {
        $user = Auth::user();

        $followUps = FollowUp::with(['konsumen','user','transaksi'])
            ->when(!in_array($user->role, ['admin','marketing']), function($q) use ($user){
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('followups.index', compact('followUps'));
    }

    // =========================
    // FORM CREATE
    // =========================
    public function create()
    {
        $konsumens = Konsumen::all();
        $transaksis = Transaksi::latest()->get(); // 🔥 tambah ini

        return view('followups.create', compact('konsumens','transaksis'));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'transaksi_id' => 'required|exists:transaksis,id', // 🔥 wajib
            'status' => 'required|in:Belum Dihubungi,Belum Bayar,Sudah Bayar',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        FollowUp::create([
            'konsumen_id' => $request->konsumen_id,
            'transaksi_id' => $request->transaksi_id, // 🔥 penting
            'status' => $request->status,
            'catatan' => $request->catatan,
            'follow_up_date' => $request->follow_up_date,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('followups.index')
            ->with('success', 'Follow-up berhasil ditambahkan!');
    }

    // =========================
    // FORM EDIT
    // =========================
    public function edit($id)
    {
        $user = Auth::user();

        $followUp = FollowUp::findOrFail($id);

        if(!in_array($user->role, ['admin','marketing']) && $followUp->user_id != $user->id){
            return redirect()->route('followups.index')
                ->with('error','Tidak punya akses');
        }

        $konsumens = Konsumen::all();
        $transaksis = Transaksi::latest()->get(); // 🔥 tambah ini

        return view('followups.edit', compact('followUp','konsumens','transaksis'));
    }

    // =========================
    // UPDATE + AUTO SYNC 🔥
    // =========================
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $followUp = FollowUp::findOrFail($id);

        if(!in_array($user->role, ['admin','marketing']) && $followUp->user_id != $user->id){
            return redirect()->route('followups.index')
                ->with('error','Tidak punya akses');
        }

        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'transaksi_id' => 'required|exists:transaksis,id', // 🔥 wajib
            'status' => 'required|in:Belum Dihubungi,Belum Bayar,Sudah Bayar',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        // =========================
        // ✅ UPDATE FOLLOW UP
        // =========================
        $followUp->update([
            'konsumen_id' => $request->konsumen_id,
            'transaksi_id' => $request->transaksi_id, // 🔥 penting
            'status' => $request->status,
            'catatan' => $request->catatan,
            'follow_up_date' => $request->follow_up_date
        ]);

        // =========================
        // 🔥 AUTO UPDATE TRANSAKSI (FIX)
        // =========================
        $transaksi = Transaksi::find($request->transaksi_id);

        if ($transaksi) {

            if ($request->status == 'Sudah Bayar') {
                $transaksi->status = 'Lunas';
            }

            if ($request->status == 'Belum Bayar') {
                $transaksi->status = 'Belum Bayar';
            }

            $transaksi->save();
        }

        // =========================
        // 🔥 AUTO UPDATE KONSUMEN
        // =========================
        $konsumen = Konsumen::find($request->konsumen_id);

        if ($konsumen) {

            if ($request->status == 'Sudah Bayar') {
                $konsumen->status = 'Deal';
            }

            if ($request->status == 'Belum Bayar') {
                $konsumen->status = 'Prospek';
            }

            $konsumen->save();
        }

        return redirect()->route('followups.index')
            ->with('success','Data berhasil diupdate & transaksi ikut diperbarui');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        $user = Auth::user();

        $followUp = FollowUp::findOrFail($id);

        if(!in_array($user->role, ['admin','marketing']) && $followUp->user_id != $user->id){
            return redirect()->route('followups.index')
                ->with('error','Tidak memiliki izin');
        }

        $followUp->delete();

        return redirect()->route('followups.index')
            ->with('success', 'Follow-up berhasil dihapus!');
    }
}
