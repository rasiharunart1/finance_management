<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Desa;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('desa')->latest()->paginate(10);
        $desas = Desa::orderBy('nama_desa')->get();

        return view('user.index', compact('users', 'desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:superadmin,admin_bendahara',
            'desa_id' => 'nullable|exists:desas,id',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);

        ActivityLog::log('Tambah User', 'Menambahkan user baru: ' . $user->name . ' (' . $user->role . ')');

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:superadmin,admin_bendahara',
            'desa_id' => 'nullable|exists:desas,id',
            'is_active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        ActivityLog::log('Update User', 'Memperbarui data user: ' . $user->name);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $nama = $user->name;
        $user->delete();

        ActivityLog::log('Hapus User', 'Menghapus user: ' . $nama);

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
