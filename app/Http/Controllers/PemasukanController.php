<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKeuangan;
use App\Models\Acara;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = TransaksiKeuangan::with('acara.desa')->where('tipe', 'pemasukan');
        $acarasQuery = Acara::with('desa');

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
            $acarasQuery->where('desa_id', $user->desa_id);
        }

        $pemasukans = $query->latest()->paginate(15);
        $acaras = $acarasQuery->orderBy('nama_acara')->get();
        $totalPemasukan = (float) $query->sum('jumlah');

        return view('pemasukan.index', compact('pemasukans', 'acaras', 'totalPemasukan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:500',
            'kategori' => 'nullable|string|max:100',
            'bukti_file' => 'nullable|image|max:3072',
        ]);

        $validated['tipe'] = 'pemasukan';
        $validated['user_id'] = $request->user()->id;
        $validated['nomor_transaksi'] = 'IN-' . strtoupper(substr(md5(uniqid()), 0, 6));
        $validated['kategori'] = $validated['kategori'] ?? 'Sponsor / Iuran';

        if ($request->hasFile('bukti_file')) {
            $validated['bukti_file'] = $request->file('bukti_file')->store('bukti_transaksi', 'public');
        }

        TransaksiKeuangan::create($validated);
        ActivityLog::log('Tambah Pemasukan', 'Mencatat kas masuk Rp ' . number_format($validated['jumlah'], 0, ',', '.'));

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil ditambahkan!');
    }

    public function update(Request $request, TransaksiKeuangan $pemasukan)
    {
        $validated = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:500',
            'kategori' => 'nullable|string|max:100',
            'bukti_file' => 'nullable|image|max:3072',
        ]);

        $validated['kategori'] = $validated['kategori'] ?? 'Sponsor / Iuran';

        if ($request->hasFile('bukti_file')) {
            $validated['bukti_file'] = $request->file('bukti_file')->store('bukti_transaksi', 'public');
        }

        $pemasukan->update($validated);
        ActivityLog::log('Update Pemasukan', 'Memperbarui kas masuk Rp ' . number_format($validated['jumlah'], 0, ',', '.'));

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil diperbarui!');
    }

    public function destroy(TransaksiKeuangan $pemasukan)
    {
        $ket = $pemasukan->keterangan;
        $pemasukan->delete();
        ActivityLog::log('Hapus Pemasukan', 'Menghapus data pemasukan: ' . $ket);

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil dihapus!');
    }
}
