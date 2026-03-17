<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Konsumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    // =========================
    // TAMPILKAN DATA FOLLOW UP
    // =========================
    public function index()
    {
        $user = Auth::user();

        $followUps = FollowUp::with(['konsumen','user'])
            ->when(!in_array($user->role, ['admin','marketing']), function($q) use ($user){
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('followups.index', compact('followUps'));
    }


    // =========================
    // FORM TAMBAH FOLLOW UP
    // =========================
    public function create()
    {
        $konsumens = Konsumen::all();

        return view('followups.create', compact('konsumens'));
    }


    // =========================
    // SIMPAN FOLLOW UP
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'konsumen_id' => 'required|exists:konsumens,id',
            'status' => 'required|string',
            'catatan' => 'nullable|string',
            'follow_up_date' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        FollowUp::create([
            'konsumen_id' => $request->konsumen_id,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'follow_up_date' => $request->follow_up_date,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('followups.index')
            ->with('success', 'Follow-up berhasil ditambahkan!');
    }


    // =========================
    // FORM EDIT FOLLOW UP
    // =========================
    public function edit($id)
{
    $followUp = FollowUp::findOrFail($id);
    $konsumens = Konsumen::all();

    return view('followups.edit', compact('followUp','konsumens'));
}

    // =========================
    // UPDATE FOLLOW UP
    // =========================
   public function update(Request $request, $id)
{
    $followUp = FollowUp::findOrFail($id);

    $request->validate([
        'konsumen_id' => 'required',
        'status' => 'required',
        'catatan' => 'nullable',
        'follow_up_date' => 'nullable'
    ]);

    $followUp->update([
        'konsumen_id' => $request->konsumen_id,
        'status' => $request->status,
        'catatan' => $request->catatan,
        'follow_up_date' => $request->follow_up_date
    ]);

    return redirect()->route('followups.index')
        ->with('success','Data berhasil diupdate');
}


    // =========================
    // HAPUS FOLLOW UP
    // =========================
    public function destroy(FollowUp $followUp)
    {
        $user = Auth::user();

        if(!in_array($user->role, ['admin','marketing']) && $followUp->user_id != $user->id){
            return redirect()->route('followups.index')
                ->with('error','Tidak memiliki izin menghapus follow-up ini');
        }

        $followUp->delete();

        return redirect()->route('followups.index')
            ->with('success', 'Follow-up berhasil dihapus!');
    }
}
