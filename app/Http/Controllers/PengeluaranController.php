<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKeuangan;
use App\Models\Acara;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = TransaksiKeuangan::with('acara.desa')->where('tipe', 'pengeluaran');
        $acarasQuery = Acara::with('desa');

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
            $acarasQuery->where('desa_id', $user->desa_id);
        }

        $pengeluarans = $query->latest()->paginate(15);
        $acaras = $acarasQuery->orderBy('nama_acara')->get();
        $totalPengeluaran = (float) $query->sum('jumlah');

        return view('pengeluaran.index', compact('pengeluarans', 'acaras', 'totalPengeluaran'));
    }

    public function store(Request $request)
    {
        if ($request->user() && $request->user()->isSuperadmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                abort(403, 'Superadmin hanya bertugas memantau instansi dan tidak berhak mencatat pengeluaran.');
            }
            return redirect()->route('pengeluaran.index')->with('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mencatat pengeluaran.');
        }

        $validated = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:500',
            'kategori' => 'nullable|string|max:100',
            'bukti_file' => 'nullable|image|max:3072',
        ]);

        $validated['tipe'] = 'pengeluaran';
        $validated['user_id'] = $request->user()->id;
        $validated['nomor_transaksi'] = 'OUT-' . strtoupper(substr(md5(uniqid()), 0, 6));
        $validated['kategori'] = $validated['kategori'] ?? 'Operasional / Hadiah';

        if ($request->hasFile('bukti_file')) {
            $validated['bukti_file'] = $request->file('bukti_file')->store('bukti_transaksi', 'public');
        }

        TransaksiKeuangan::create($validated);
        ActivityLog::log('Tambah Pengeluaran', 'Mencatat kas keluar Rp ' . number_format($validated['jumlah'], 0, ',', '.'));

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dicatat!');
    }

    public function update(Request $request, TransaksiKeuangan $pengeluaran)
    {
        if ($request->user() && $request->user()->isSuperadmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                abort(403, 'Superadmin hanya bertugas memantau instansi dan tidak berhak mengubah data pengeluaran.');
            }
            return redirect()->route('pengeluaran.index')->with('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mengubah data pengeluaran.');
        }

        $validated = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:500',
            'kategori' => 'nullable|string|max:100',
            'bukti_file' => 'nullable|image|max:3072',
        ]);

        $validated['kategori'] = $validated['kategori'] ?? 'Operasional / Hadiah';

        if ($request->hasFile('bukti_file')) {
            $validated['bukti_file'] = $request->file('bukti_file')->store('bukti_transaksi', 'public');
        }

        $pengeluaran->update($validated);
        ActivityLog::log('Update Pengeluaran', 'Memperbarui kas keluar Rp ' . number_format($validated['jumlah'], 0, ',', '.'));

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    public function destroy(TransaksiKeuangan $pengeluaran)
    {
        if (auth()->user() && auth()->user()->isSuperadmin()) {
            if (request()->expectsJson() || request()->ajax()) {
                abort(403, 'Superadmin hanya bertugas memantau instansi dan tidak berhak menghapus data pengeluaran.');
            }
            return redirect()->route('pengeluaran.index')->with('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak menghapus data pengeluaran.');
        }

        $ket = $pengeluaran->keterangan;
        $pengeluaran->delete();
        ActivityLog::log('Hapus Pengeluaran', 'Menghapus data pengeluaran: ' . $ket);

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}
