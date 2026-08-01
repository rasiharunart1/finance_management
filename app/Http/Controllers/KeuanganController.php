<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKeuangan;
use App\Models\Acara;
use App\Models\User;
use App\Models\ActivityLog;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $acaraId = $request->query('acara_id');
        $tipe = $request->query('tipe');
        $user = auth()->user();

        $query = TransaksiKeuangan::with(['acara.desa', 'user'])
            ->when($acaraId, function ($query, $acaraId) {
                return $query->where('acara_id', $acaraId);
            })
            ->when($tipe, function ($query, $tipe) {
                return $query->where('tipe', $tipe);
            });

        $acarasQuery = Acara::with('desa');

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
            $acarasQuery->where('desa_id', $user->desa_id);
        }

        $transaksis = $query->latest()->paginate(12)->withQueryString();
        $acaras = $acarasQuery->orderBy('nama_acara')->get();

        $pemasukanQuery = TransaksiKeuangan::where('tipe', 'pemasukan');
        $pengeluaranQuery = TransaksiKeuangan::where('tipe', 'pengeluaran');

        if ($user && $user->isBendahara() && $user->desa_id) {
            $pemasukanQuery->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
            $pengeluaranQuery->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        }

        $isBendahara = $user && $user->isBendahara() && $user->desa_id;
        $modalAwal = (float) ($isBendahara ? ($user->desa->modal_awal ?? 25000000) : \App\Models\Desa::sum('modal_awal'));
        $totalPemasukan = (float) $pemasukanQuery->sum('jumlah');
        $totalPengeluaran = (float) $pengeluaranQuery->sum('jumlah');
        $saldoKas = $modalAwal + $totalPemasukan - $totalPengeluaran;

        return view('keuangan.index', compact('transaksis', 'acaras', 'acaraId', 'tipe', 'totalPemasukan', 'totalPengeluaran', 'saldoKas', 'modalAwal'));
    }

    public function store(Request $request)
    {
        if ($request->user() && $request->user()->isSuperadmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                abort(403, 'Superadmin hanya bertugas memantau instansi dan tidak berhak mencatat transaksi kas.');
            }
            return redirect()->route('keuangan.index')->with('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mencatat transaksi kas.');
        }

        $validated = $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'required|string|max:500',
            'bukti_file' => 'nullable|image|max:3072',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['nomor_transaksi'] = 'TRX-' . strtoupper(substr(md5(uniqid()), 0, 6));
        $validated['kategori'] = $validated['tipe'] === 'pemasukan' ? 'Dana Kas' : 'Realisasi Acara';

        if ($request->hasFile('bukti_file')) {
            $validated['bukti_file'] = $request->file('bukti_file')->store('bukti_transaksi', 'public');
        }

        $trx = TransaksiKeuangan::create($validated);

        ActivityLog::log(
            'Catat Kas Bendahara',
            'Mencatat ' . $trx->tipe . ' sebesar Rp ' . number_format($trx->jumlah, 0, ',', '.') . ' untuk acara #' . $trx->acara_id
        );

        // Notify Superadmins
        $admins = User::where('role', 'superadmin')->get();
        Notification::send($admins, new SystemNotification(
            'Realisasi Kas ' . ucfirst($trx->tipe),
            'Bendahara mencatat ' . $trx->tipe . ' Rp ' . number_format($trx->jumlah, 0, ',', '.') . ' (' . $trx->keterangan . ')',
            $trx->tipe === 'pemasukan' ? 'fa-solid fa-arrow-down-long' : 'fa-solid fa-arrow-up-long',
            route('keuangan.index')
        ));

        return redirect()->route('keuangan.index')->with('success', 'Transaksi kas berhasil dicatat!');
    }

    public function destroy(TransaksiKeuangan $keuangan)
    {
        if (auth()->user() && auth()->user()->isSuperadmin()) {
            if (request()->expectsJson() || request()->ajax()) {
                abort(403, 'Superadmin hanya bertugas memantau instansi dan tidak berhak menghapus transaksi kas.');
            }
            return redirect()->route('keuangan.index')->with('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak menghapus transaksi kas.');
        }

        $no = $keuangan->nomor_transaksi;
        $keuangan->delete();

        ActivityLog::log('Hapus Transaksi', 'Menghapus transaksi kas: ' . $no);

        return redirect()->route('keuangan.index')->with('success', 'Transaksi kas berhasil dihapus!');
    }

    public function exportPrint(Request $request)
    {
        $user = auth()->user();
        $acaraId = $request->query('acara_id');
        $tipe = $request->query('tipe');

        $query = TransaksiKeuangan::with(['acara.desa', 'user'])
            ->when($acaraId, function ($query, $acaraId) {
                return $query->where('acara_id', $acaraId);
            })
            ->when($tipe, function ($query, $tipe) {
                return $query->where('tipe', $tipe);
            });

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        }

        $transaksis = $query->oldest('tanggal_transaksi')->get();

        $pemasukanQuery = TransaksiKeuangan::where('tipe', 'pemasukan');
        $pengeluaranQuery = TransaksiKeuangan::where('tipe', 'pengeluaran');

        if ($user && $user->isBendahara() && $user->desa_id) {
            $pemasukanQuery->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
            $pengeluaranQuery->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        }

        $isBendahara = $user && $user->isBendahara() && $user->desa_id;
        $modalAwal = (float) ($isBendahara ? ($user->desa->modal_awal ?? 25000000) : \App\Models\Desa::sum('modal_awal'));
        $totalPemasukan = (float) $pemasukanQuery->sum('jumlah');
        $totalPengeluaran = (float) $pengeluaranQuery->sum('jumlah');
        $saldoKas = $modalAwal + $totalPemasukan - $totalPengeluaran;
        $namaDesa = $isBendahara ? ($user->desa->nama_desa ?? '-') : 'Seluruh Desa / Tingkat Kabupaten';

        return view('keuangan.print', compact(
            'transaksis',
            'modalAwal',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKas',
            'namaDesa'
        ));
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $acaraId = $request->query('acara_id');
        $tipe = $request->query('tipe');

        $query = TransaksiKeuangan::with(['acara.desa', 'user'])
            ->when($acaraId, function ($query, $acaraId) {
                return $query->where('acara_id', $acaraId);
            })
            ->when($tipe, function ($query, $tipe) {
                return $query->where('tipe', $tipe);
            });

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->whereHas('acara', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        }

        $transaksis = $query->oldest('tanggal_transaksi')->get();

        $filename = "laporan_keuangan_nhfinance_hut79_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No',
            'Nomor Transaksi',
            'Tanggal',
            'Desa / Lingkup',
            'Acara Terkait',
            'Tipe Transaksi',
            'Kategori / Keterangan',
            'Pemasukan (Rp)',
            'Pengeluaran (Rp)',
            'Dicatat Oleh'
        ];

        $callback = function () use ($transaksis, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');

            $no = 1;
            foreach ($transaksis as $t) {
                $in = $t->tipe === 'pemasukan' ? $t->jumlah : 0;
                $out = $t->tipe === 'pengeluaran' ? $t->jumlah : 0;

                fputcsv($file, [
                    $no++,
                    $t->nomor_transaksi,
                    $t->tanggal_transaksi->format('d-m-Y'),
                    $t->acara->desa->nama_desa ?? 'Umum',
                    $t->acara->nama_acara ?? '-',
                    strtoupper($t->tipe),
                    $t->keterangan ?? '-',
                    $in,
                    $out,
                    $t->user->name ?? '-'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
