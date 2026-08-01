<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use App\Models\Desa;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StrukturController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->isBendahara() && $user->desa_id) {
            $panitias = Panitia::with('desa')->where('desa_id', $user->desa_id)->latest()->get();
        } else {
            $panitias = Panitia::with('desa')->latest()->get();
        }
        $desas = Desa::all();

        return view('struktur.index', compact('panitias', 'desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $validated['avatar'] = strtoupper(substr($validated['nama'], 0, 2));

        if (auth()->user() && auth()->user()->isBendahara()) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        Panitia::create($validated);
        ActivityLog::log('Tambah Panitia', 'Menambahkan anggota struktur panitia: ' . $validated['nama']);

        return redirect()->route('struktur.index')->with('success', 'Anggota panitia berhasil ditambahkan!');
    }

    public function update(Request $request, Panitia $struktur)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|string',
            'phone' => 'nullable|string|max:50',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $validated['avatar'] = strtoupper(substr($validated['nama'], 0, 2));

        if (auth()->user() && auth()->user()->isBendahara()) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        $struktur->update($validated);
        ActivityLog::log('Update Panitia', 'Memperbarui data panitia: ' . $validated['nama']);

        return redirect()->route('struktur.index')->with('success', 'Data panitia berhasil diperbarui!');
    }

    public function destroy(Panitia $struktur)
    {
        $nama = $struktur->nama;
        $struktur->delete();
        ActivityLog::log('Hapus Panitia', 'Menghapus anggota panitia: ' . $nama);

        return redirect()->route('struktur.index')->with('success', 'Anggota panitia berhasil dihapus!');
    }
}
