<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Acara;
use App\Models\TransaksiKeuangan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isBendahara = $user && $user->isBendahara() && $user->desa_id;
        $desaId = $isBendahara ? $user->desa_id : null;

        $totalDesa = $isBendahara ? 1 : Desa::count();
        $totalAcara = $isBendahara ? Acara::where('desa_id', $desaId)->count() : Acara::count();

        $totalAnggaran = (float) ($isBendahara ? Acara::where('desa_id', $desaId)->sum('anggaran_rencana') : Acara::sum('anggaran_rencana'));

        $pemasukanQuery = TransaksiKeuangan::where('tipe', 'pemasukan');
        $pengeluaranQuery = TransaksiKeuangan::where('tipe', 'pengeluaran');

        if ($isBendahara) {
            $pemasukanQuery->whereHas('acara', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            });
            $pengeluaranQuery->whereHas('acara', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            });
        }

        $modalAwal = (float) ($isBendahara ? ($user->desa->modal_awal ?? 25000000) : Desa::sum('modal_awal'));
        $totalPemasukan = (float) $pemasukanQuery->sum('jumlah');
        $totalPengeluaran = (float) $pengeluaranQuery->sum('jumlah');
        $saldoKas = $modalAwal + $totalPemasukan - $totalPengeluaran;

        // Status Chart Data
        $acaraQuery = Acara::query();
        if ($isBendahara) {
            $acaraQuery->where('desa_id', $desaId);
        }

        $acaraOngoing = (clone $acaraQuery)->where('status', 'ongoing')->count();
        $acaraPlanned = (clone $acaraQuery)->where('status', 'planned')->count();
        $acaraCompleted = (clone $acaraQuery)->where('status', 'completed')->count();
        $acaraCancelled = (clone $acaraQuery)->where('status', 'cancelled')->count();

        // Anggaran Chart per Acara (Top 5)
        $topAcaras = (clone $acaraQuery)->with('transaksis')->latest()->take(5)->get();
        $chartLabels = [];
        $chartAnggaranData = [];
        $chartRealisasiData = [];

        foreach ($topAcaras as $a) {
            $chartLabels[] = $a->nama_acara;
            $chartAnggaranData[] = round($a->anggaran_rencana / 1000000, 2); // dalam juta
            $chartRealisasiData[] = round($a->total_pengeluaran / 1000000, 2); // dalam juta
        }

        $latestAcaras = (clone $acaraQuery)->with('desa')->latest()->take(6)->get();
        $latestActivities = ActivityLog::with('user')->latest()->take(6)->get();

        // Trend Chart Data (Line Chart Pemasukan vs Pengeluaran)
        $trendLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', '17 Agustus', 'Pasca Event'];
        
        if ($totalPemasukan == 0 && $totalPengeluaran == 0) {
            $trendPemasukan = [0, 0, 0, 0, 0, 0];
            $trendPengeluaran = [0, 0, 0, 0, 0, 0];
        } else {
            $baseIn = $totalPemasukan;
            $baseOut = $totalPengeluaran;

            $trendPemasukan = [
                round(($baseIn * 0.12) / 1000000, 2),
                round(($baseIn * 0.22) / 1000000, 2),
                round(($baseIn * 0.35) / 1000000, 2),
                round(($baseIn * 0.18) / 1000000, 2),
                round(($baseIn * 0.10) / 1000000, 2),
                round(($baseIn * 0.03) / 1000000, 2),
            ];

            $trendPengeluaran = [
                round(($baseOut * 0.08) / 1000000, 2),
                round(($baseOut * 0.18) / 1000000, 2),
                round(($baseOut * 0.28) / 1000000, 2),
                round(($baseOut * 0.26) / 1000000, 2),
                round(($baseOut * 0.16) / 1000000, 2),
                round(($baseOut * 0.04) / 1000000, 2),
            ];
        }

        return view('dashboard', compact(
            'totalDesa',
            'totalAcara',
            'totalAnggaran',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKas',
            'modalAwal',
            'acaraOngoing',
            'acaraPlanned',
            'acaraCompleted',
            'acaraCancelled',
            'chartLabels',
            'chartAnggaranData',
            'chartRealisasiData',
            'latestAcaras',
            'latestActivities',
            'trendLabels',
            'trendPemasukan',
            'trendPengeluaran'
        ));
    }
}
