<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Desa;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->isBendahara() && $user->desa_id) {
            $dokumens = Dokumen::with('desa')->where('desa_id', $user->desa_id)->latest()->get();
        } else {
            $dokumens = Dokumen::with('desa')->latest()->get();
        }
        $desas = Desa::all();

        return view('dokumen.index', compact('dokumens', 'desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tipe_file' => 'required|string|max:20',
            'status' => 'required|string',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $validated['ukuran_file'] = rand(1, 4) . '.' . rand(1, 9) . ' MB';
        $validated['user_id'] = $request->user()->id;

        if (auth()->user() && auth()->user()->isBendahara()) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        Dokumen::create($validated);
        ActivityLog::log('Upload Dokumen', 'Menambahkan dokumen baru: ' . $validated['nama_dokumen']);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diarsip!');
    }

    public function update(Request $request, Dokumen $dokuman)
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tipe_file' => 'required|string|max:20',
            'status' => 'required|string',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        if (auth()->user() && auth()->user()->isBendahara()) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        $dokuman->update($validated);
        ActivityLog::log('Update Dokumen', 'Memperbarui data arsip: ' . $validated['nama_dokumen']);

        return redirect()->route('dokumen.index')->with('success', 'Arsip dokumen berhasil diperbarui!');
    }

    public function destroy(Dokumen $dokuman)
    {
        $nama = $dokuman->nama_dokumen;
        $dokuman->delete();
        ActivityLog::log('Hapus Dokumen', 'Menghapus arsip dokumen: ' . $nama);

        return redirect()->route('dokumen.index')->with('success', 'Arsip dokumen berhasil dihapus!');
    }
}
