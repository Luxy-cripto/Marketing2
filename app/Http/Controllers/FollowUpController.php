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
    // LIST DATA (FOLLOW UP AKTIF)
    // =========================
    public function index()
    {
        $user = Auth::user();

        $followUps = FollowUp::with([
                'konsumen',
                'user',
                'transaksi.details.produk'
            ])
            ->when(!in_array($user->role, ['admin','marketing']), function($q) use ($user){
                $q->where('user_id', $user->id);
            })
            ->where('status','!=','Sudah Bayar') // ✅ hanya yg aktif
            ->latest()
            ->get();

        return view('followups.index', compact('followUps'));
    }

    // =========================
    // RIWAYAT FOLLOW UP
    // =========================
    public function riwayat()
    {
        $user = Auth::user();

        $riwayat = FollowUp::with([
                'konsumen',
                'user',
                'transaksi.details.produk'
            ])
            ->when(!in_array($user->role, ['admin','marketing']), function($q) use ($user){
                $q->where('user_id', $user->id);
            })
            ->where('status','Sudah Bayar') // ✅ riwayat
            ->latest()
            ->get();

        return view('followups.riwayat', compact('riwayat'));
    }

    // =========================
    // FORM CREATE
    // =========================
    public function create()
    {
        return view('followups.create', [
            'konsumens' => Konsumen::all(),
            'transaksis' => Transaksi::with(['konsumen','details.produk'])->latest()->get()
        ]);
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'transaksi_id' => 'required|exists:transaksis,id',
            'status' => 'required|in:Belum Dihubungi,Belum Bayar,Sudah Bayar',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        $followUp = FollowUp::create([
        'konsumen_id' => $request->konsumen_id,
        'transaksi_id' => $request->transaksi_id, // ✅ FIX
        'status' => $request->status,
        'catatan' => $request->catatan,
        'follow_up_date' => $request->follow_up_date,
        'user_id' => Auth::id(),
    ]);

        // 🔥 SYNC TRANSAKSI
        $transaksi = Transaksi::find($request->transaksi_id);
        if ($transaksi) {
            if ($request->status == 'Sudah Bayar') {
                $transaksi->status = 'Lunas';
            } elseif ($request->status == 'Belum Bayar') {
                $transaksi->status = 'Belum Bayar';
            }
            $transaksi->save();
        }

        // 🔥 SYNC KONSUMEN
        $konsumen = Konsumen::find($request->konsumen_id);
        if ($konsumen) {
            if ($request->status == 'Sudah Bayar') {
                $konsumen->status = 'Deal';
            } elseif ($request->status == 'Belum Bayar') {
                $konsumen->status = 'Prospek';
            }
            $konsumen->save();
        }

        return redirect()->route('followups.index')
            ->with('success', 'Follow-up berhasil ditambahkan!');
    }

    // =========================
    // FORM EDIT
    // =========================
    public function edit($id)
    {
        $user = Auth::user();

        $followUp = FollowUp::with('transaksi.details.produk')->findOrFail($id);

        if(!in_array($user->role, ['admin','marketing']) && $followUp->user_id != $user->id){
            return redirect()->route('followups.index')
                ->with('error','Tidak punya akses');
        }

        return view('followups.edit', [
            'followUp' => $followUp,
            'konsumens' => Konsumen::all(),
            'transaksis' => Transaksi::with(['konsumen','details.produk'])->latest()->get()
        ]);
    }

    // =========================
    // UPDATE (🔥 TANPA HAPUS)
    // =========================
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $followUp = FollowUp::findOrFail($id);

        if (!in_array($user->role, ['admin','marketing']) && $followUp->user_id != $user->id) {
            return redirect()->route('followups.index')
                ->with('error','Tidak punya akses');
        }

        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'transaksi_id' => 'required|exists:transaksis,id',
            'status' => 'required|in:Belum Dihubungi,Belum Bayar,Sudah Bayar',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        // ✅ UPDATE DATA
        $followUp->update([
            'konsumen_id' => $request->konsumen_id,
            'transaksi_id' => $request->transaksi_id,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'follow_up_date' => $request->follow_up_date
        ]);

        // 🔥 SYNC TRANSAKSI
        $transaksi = Transaksi::find($request->transaksi_id);
        if ($transaksi) {
            if ($request->status == 'Sudah Bayar') {
                $transaksi->status = 'Lunas';
            } elseif ($request->status == 'Belum Bayar') {
                $transaksi->status = 'Belum Bayar';
            }
            $transaksi->save();
        }

        // 🔥 SYNC KONSUMEN
        $konsumen = Konsumen::find($request->konsumen_id);
        if ($konsumen) {
            if ($request->status == 'Sudah Bayar') {
                $konsumen->status = 'Deal';
            } elseif ($request->status == 'Belum Bayar') {
                $konsumen->status = 'Prospek';
            }
            $konsumen->save();
        }

        return redirect()->route('followups.index')
            ->with('success','Status berhasil diupdate');
    }

    // =========================
    // DELETE (MANUAL)
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
