<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar user marketing saja
    public function index()
    {
        $users = User::where('role', 'marketing')->latest()->get();
        return view('users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'marketing', // paksa marketing
            'is_active' => 1
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    // Form edit
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_active' => 'required|in:0,1',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    // Detail user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
