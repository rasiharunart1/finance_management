<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\Desa;
use App\Models\Acara;
use App\Models\TransaksiKeuangan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->isBendahara() && $user->desa_id) {
            $sponsors = Sponsor::with('desa')->where('desa_id', $user->desa_id)->latest()->get();
        } else {
            $sponsors = Sponsor::with('desa')->latest()->get();
        }
        $desas = Desa::all();

        return view('sponsor.index', compact('sponsors', 'desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sponsor' => 'required|string|max:255',
            'paket' => 'required|string|max:100',
            'nominal_proposal' => 'required|numeric|min:0',
            'divisi_pj' => 'nullable|string|max:255',
            'status' => 'required|string',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $validated['tanggal_update'] = now();

        if (auth()->user() && auth()->user()->isBendahara()) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        $sponsor = Sponsor::create($validated);
        ActivityLog::log('Tambah Prospek Sponsor', 'Menambahkan prospek sponsor baru: ' . $validated['nama_sponsor']);

        $this->handleAutoPemasukan($sponsor);

        return redirect()->route('sponsor.index')->with('success', 'Prospek sponsor baru berhasil ditambahkan!');
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'nama_sponsor' => 'required|string|max:255',
            'paket' => 'required|string|max:100',
            'nominal_proposal' => 'required|numeric|min:0',
            'divisi_pj' => 'nullable|string|max:255',
            'status' => 'required|string',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $validated['tanggal_update'] = now();

        if (auth()->user() && auth()->user()->isBendahara()) {
            $validated['desa_id'] = auth()->user()->desa_id;
        }

        $sponsor->update($validated);
        ActivityLog::log('Update Sponsor', 'Memperbarui status/data sponsor: ' . $validated['nama_sponsor']);

        $this->handleAutoPemasukan($sponsor);

        return redirect()->route('sponsor.index')->with('success', 'Data sponsor berhasil diperbarui & sinkron ke kas!');
    }

    public function destroy(Sponsor $sponsor)
    {
        $nama = $sponsor->nama_sponsor;
        $sponsor->delete();
        ActivityLog::log('Hapus Sponsor', 'Menghapus data sponsor: ' . $nama);

        return redirect()->route('sponsor.index')->with('success', 'Data sponsor berhasil dihapus!');
    }

    public function confirmLunas(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'nominal_final' => 'required|numeric|min:0',
            'keterangan_cair' => 'nullable|string|max:255',
            'bukti_struk' => 'nullable|image|max:3072',
        ]);

        $path = $sponsor->bukti_struk;
        if ($request->hasFile('bukti_struk')) {
            $path = $request->file('bukti_struk')->store('bukti_sponsor', 'public');
        }

        $sponsor->update([
            'status' => 'lunas',
            'nominal_final' => $validated['nominal_final'],
            'bukti_struk' => $path,
            'tanggal_update' => now(),
        ]);

        // Check if already auto-recorded
        $exists = TransaksiKeuangan::where('keterangan', 'like', '%Sponsor: ' . $sponsor->nama_sponsor . '%')->exists();
        if (!$exists) {
            $acara = Acara::where('desa_id', $sponsor->desa_id)->first() ?? Acara::first();
            if ($acara) {
                TransaksiKeuangan::create([
                    'acara_id' => $acara->id,
                    'user_id' => auth()->id() ?? 1,
                    'nomor_transaksi' => 'SPON-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                    'tipe' => 'pemasukan',
                    'kategori' => 'Sponsorship Lunas',
                    'jumlah' => $validated['nominal_final'],
                    'tanggal_transaksi' => now(),
                    'keterangan' => 'Sponsor: ' . $sponsor->nama_sponsor . ' (' . $sponsor->paket . ')' . ($validated['keterangan_cair'] ? ' - ' . $validated['keterangan_cair'] : ''),
                    'bukti_file' => $path,
                ]);

                ActivityLog::log(
                    'Konfirmasi Lunas Sponsor',
                    'Mencairkan dana sponsor ' . $sponsor->nama_sponsor . ' dengan nominal final Rp ' . number_format($validated['nominal_final'], 0, ',', '.')
                );
            }
        }

        return redirect()->route('sponsor.index')->with('success', 'Sponsor ' . $sponsor->nama_sponsor . ' resmi Lunas! Kas masuk Rp ' . number_format($validated['nominal_final'], 0, ',', '.') . ' otomatis tercatat ke kas & RAB.');
    }

    protected function handleAutoPemasukan(Sponsor $sponsor)
    {
        $statusLower = strtolower($sponsor->status);
        if (in_array($statusLower, ['lunas', 'disetujui', 'paid', 'success'])) {
            // Check if already auto-recorded
            $exists = TransaksiKeuangan::where('keterangan', 'like', '%Sponsor: ' . $sponsor->nama_sponsor . '%')->exists();
            if (!$exists) {
                $acara = Acara::where('desa_id', $sponsor->desa_id)->first() ?? Acara::first();
                if ($acara) {
                    $nominal = $sponsor->nominal_final ?: $sponsor->nominal_proposal;
                    TransaksiKeuangan::create([
                        'acara_id' => $acara->id,
                        'user_id' => auth()->id() ?? 1,
                        'nomor_transaksi' => 'SPON-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                        'tipe' => 'pemasukan',
                        'kategori' => 'Sponsorship Lunas',
                        'jumlah' => $nominal,
                        'tanggal_transaksi' => now(),
                        'keterangan' => 'Sponsor: ' . $sponsor->nama_sponsor . ' (' . $sponsor->paket . ')',
                    ]);

                    ActivityLog::log(
                        'Auto Pemasukan Sponsor',
                        'Otomatis mencatat dana kas masuk Rp ' . number_format($nominal, 0, ',', '.') . ' dari sponsor: ' . $sponsor->nama_sponsor
                    );
                }
            }
        }
    }
}
