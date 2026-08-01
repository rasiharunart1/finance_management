<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\User;
use App\Models\ActivityLog;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $desas = Desa::withCount('acaras')
            ->when($search, function ($query, $search) {
                return $query->where('nama_desa', 'like', "%{$search}%")
                    ->orWhere('kode_desa', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('desa.index', compact('desas', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_desa' => 'required|string|max:50|unique:desas,kode_desa',
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kepala_desa' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'populasi' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
            'catatan' => 'nullable|string',
        ]);

        $desa = Desa::create($validated);

        ActivityLog::log('Tambah Desa', 'Menambahkan data desa baru: ' . $desa->nama_desa);

        // Send realtime notification to all superadmins
        $admins = User::where('role', 'superadmin')->get();
        Notification::send($admins, new SystemNotification(
            'Desa Baru Ditambahkan',
            'Desa "' . $desa->nama_desa . '" (' . $desa->kode_desa . ') baru saja didaftarkan di sistem.',
            'fa-solid fa-map-location-dot',
            route('desa.index')
        ));

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil ditambahkan!');
    }

    public function update(Request $request, Desa $desa)
    {
        $validated = $request->validate([
            'kode_desa' => 'required|string|max:50|unique:desas,kode_desa,' . $desa->id,
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kepala_desa' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'populasi' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
            'catatan' => 'nullable|string',
        ]);

        $desa->update($validated);

        ActivityLog::log('Update Desa', 'Memperbarui data desa: ' . $desa->nama_desa);

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil diperbarui!');
    }

    public function destroy(Desa $desa)
    {
        if ($desa->acaras()->count() > 0) {
            return redirect()->route('desa.index')->with('error', 'Desa tidak dapat dihapus karena masih memiliki relasi agenda acara!');
        }

        $nama = $desa->nama_desa;
        $desa->delete();

        ActivityLog::log('Hapus Desa', 'Menghapus data desa: ' . $nama);

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil dihapus!');
    }
}
