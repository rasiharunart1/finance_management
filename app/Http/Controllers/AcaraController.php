<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Desa;
use App\Models\User;
use App\Models\ActivityLog;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AcaraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $desaId = $request->query('desa_id');
        $status = $request->query('status');
        $user = auth()->user();

        $query = Acara::with(['desa', 'user', 'transaksis'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_acara', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            })
            ->when($desaId, function ($query, $desaId) {
                return $query->where('desa_id', $desaId);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            });

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->where('desa_id', $user->desa_id);
            $desas = Desa::where('id', $user->desa_id)->get();
        } else {
            $desas = Desa::orderBy('nama_desa')->get();
        }

        $acaras = $query->latest()->paginate(10)->withQueryString();

        return view('acara.index', compact('acaras', 'desas', 'search', 'desaId', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_acara' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'anggaran_rencana' => 'required|numeric|min:0',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        $validated['user_id'] = $request->user()->id;

        if (auth()->user() && auth()->user()->isBendahara() && auth()->user()->desa_id) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        $acara = Acara::create($validated);

        ActivityLog::log('Tambah Acara', 'Menambahkan agenda acara: ' . $acara->nama_acara);

        // Notify Superadmin and Bendahara
        $recipients = User::all();
        Notification::send($recipients, new SystemNotification(
            'Acara Baru Direncanakan',
            'Acara "' . $acara->nama_acara . '" dibuat untuk ' . ($acara->desa ? $acara->desa->nama_desa : '-') . ' dengan anggaran Rp ' . number_format($acara->anggaran_rencana, 0, ',', '.'),
            'fa-solid fa-calendar-check',
            route('acara.index')
        ));

        return redirect()->route('acara.index')->with('success', 'Agenda acara berhasil dijadwalkan!');
    }

    public function update(Request $request, Acara $acara)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_acara' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'anggaran_rencana' => 'required|numeric|min:0',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        if (auth()->user() && auth()->user()->isBendahara() && auth()->user()->desa_id) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        $acara->update($validated);

        ActivityLog::log('Update Acara', 'Memperbarui acara: ' . $acara->nama_acara);

        return redirect()->route('acara.index')->with('success', 'Agenda acara berhasil diperbarui!');
    }

    public function destroy(Acara $acara)
    {
        $nama = $acara->nama_acara;
        $acara->delete();

        ActivityLog::log('Hapus Acara', 'Menghapus acara: ' . $nama);

        return redirect()->route('acara.index')->with('success', 'Agenda acara berhasil dihapus!');
    }
}
