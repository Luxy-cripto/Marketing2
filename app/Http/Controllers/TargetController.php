<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index()
    {
        $targets = Target::with('user')->latest()->get();
        return view('targets.index', compact('targets'));
    }

    public function create()
    {
        return view('targets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'target_omset' => 'required|numeric|min:0',
            'target_lead' => 'required|integer|min:0',
        ]);

        Target::create($request->all());

        return redirect()->route('targets.index')->with('success', 'Target berhasil dibuat!');
    }

    public function edit(Target $target)
    {
        return view('targets.edit', compact('target'));
    }

    public function update(Request $request, Target $target)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'target_omset' => 'required|numeric|min:0',
            'target_lead' => 'required|integer|min:0',
        ]);

        $target->update($request->all());

        return redirect()->route('targets.index')->with('success', 'Target berhasil diupdate!');
    }

    public function destroy(Target $target)
    {
        $target->delete();
        return redirect()->route('targets.index')->with('success', 'Target berhasil dihapus!');
    }
}
