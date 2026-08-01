<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Desa;
use App\Models\TransaksiKeuangan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Acara::with('desa')->withCount('transaksis');
        $desa = null;

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->where('desa_id', $user->desa_id);
            $desa = Desa::find($user->desa_id);
            $modalAwal = (float) ($desa->modal_awal ?? 25000000);

            $totalPemasukan = (float) TransaksiKeuangan::where('tipe', 'pemasukan')
                ->whereHas('acara', function ($q) use ($user) {
                    $q->where('desa_id', $user->desa_id);
                })->sum('jumlah');

            $totalPengeluaran = (float) TransaksiKeuangan::where('tipe', 'pengeluaran')
                ->whereHas('acara', function ($q) use ($user) {
                    $q->where('desa_id', $user->desa_id);
                })->sum('jumlah');
        } else {
            $modalAwal = (float) Desa::sum('modal_awal');
            $totalPemasukan = (float) TransaksiKeuangan::where('tipe', 'pemasukan')->sum('jumlah');
            $totalPengeluaran = (float) TransaksiKeuangan::where('tipe', 'pengeluaran')->sum('jumlah');
            $desa = Desa::first();
        }

        $saldoKasAktif = $modalAwal + $totalPemasukan - $totalPengeluaran;

        $acaras = $query->latest()->get();
        $totalAnggaran = (float) $acaras->sum('anggaran_rencana');
        $totalRealisasi = (float) $acaras->sum('total_pengeluaran');
        $desas = Desa::orderBy('nama_desa')->get();

        return view('anggaran.index', compact(
            'acaras',
            'totalAnggaran',
            'totalRealisasi',
            'modalAwal',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKasAktif',
            'desa',
            'desas'
        ));
    }

    public function updateModalAwal(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required|numeric|min:0',
            'desa_id' => 'nullable|exists:desas,id',
        ]);

        $user = auth()->user();
        if ($user && $user->isSuperadmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                abort(403, 'Superadmin hanya bertugas memantau instansi dan tidak berhak mengubah modal awal.');
            }
            return redirect()->route('anggaran.index')->with('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mengubah modal awal.');
        }

        $desaId = $request->desa_id;

        if ($user && $user->isBendahara() && $user->desa_id) {
            $desaId = $user->desa_id;
        }

        $desa = Desa::find($desaId ?? 1);
        if ($desa) {
            $desa->update(['modal_awal' => $request->modal_awal]);
            ActivityLog::log(
                'Update Modal Awal',
                'Mengatur modal awal operasional Unit/Instansi ' . $desa->nama_desa . ' menjadi Rp ' . number_format($request->modal_awal, 0, ',', '.')
            );
        }

        return redirect()->route('anggaran.index')->with('success', 'Modal Awal / Kas Dasar kegiatan berhasil diperbarui!');
    }
}
