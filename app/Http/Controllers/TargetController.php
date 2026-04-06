<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use App\Models\TargetDetail;
use App\Models\Produk;

class TargetController extends Controller
{
    public function index()
    {
        $targets = Target::with('user')->latest()->get();
        return view('targets.index', compact('targets'));
    }

    public function create()
{
    // ambil semua produk yang sudah pernah dipakai di target_detail
    $usedProduk = TargetDetail::pluck('produk_id')->toArray();

    // ambil produk yang belum dipakai
    $produks = Produk::whereNotIn('id', $usedProduk)->get();

    return view('targets.create', compact('produks'));
}

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
        ]);

        // Simpan target utama
        $target = Target::create([
            'user_id' => $request->user_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'target_omset' => 0,
            'target_lead' => 0,
        ]);

        $totalOmset = 0;
        $totalQty = 0;

        // Simpan detail produk
        if ($request->produk) {
            foreach ($request->produk as $produk_id => $data) {

                $qty = $data['qty'] ?? 0;
                $omset = $data['omset'] ?? 0;

                if ($qty > 0 || $omset > 0) {

                    TargetDetail::create([
                        'target_id' => $target->id,
                        'produk_id' => $produk_id,
                        'target_qty' => $qty,
                        'target_omset_produk' => $omset,
                    ]);

                    $totalQty += $qty;
                    $totalOmset += $omset;
                }
            }
        }

        // Update total ke target utama
        $target->update([
            'target_omset' => $totalOmset,
            'target_lead' => $totalQty,
        ]);

        return redirect()->route('targets.index')
            ->with('success', 'Target berhasil dibuat!');
    }
    public function edit(Target $target)
    {
        $target->load('details');
        return view('targets.edit', compact('target'));
    }

    public function update(Request $request, Target $target)
{
    // 1. Validasi hanya field yang benar-benar ada di form utama
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'bulan'   => 'required|integer|min:1|max:12',
        'tahun'   => 'required|integer|min:2000',
    ]);

    // 2. Update data dasar Target
    $target->update([
        'user_id' => $request->user_id,
        'bulan'   => $request->bulan,
        'tahun'   => $request->tahun,
    ]);

    $totalOmset = 0;
    $totalQty = 0;

    // 3. Update atau Create Detail Produk
    if ($request->produk) {
        foreach ($request->produk as $produk_id => $data) {
            $qty = $data['qty'] ?? 0;
            $omset = $data['omset'] ?? 0;

            // Gunakan updateOrCreate agar data lama terupdate, data baru masuk
            TargetDetail::updateOrCreate(
                [
                    'target_id' => $target->id,
                    'produk_id' => $produk_id,
                ],
                [
                    'target_qty' => $qty,
                    'target_omset_produk' => $omset,
                ]
            );

            $totalQty += $qty;
            $totalOmset += $omset;
        }
    }

    // 4. Update total ke target utama (sebagai rangkuman)
    $target->update([
        'target_omset' => $totalOmset,
        'target_lead' => $totalQty,
    ]);

    return redirect()->route('targets.index')->with('success', 'Target berhasil diupdate!');
}

    public function destroy(Target $target)
    {
        $target->delete();
        return redirect()->route('targets.index')->with('success', 'Target berhasil dihapus!');
    }
}
