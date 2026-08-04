<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StrukturController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Utama (ERP Bendahara)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Panduan Manual Pengguna Pemula (Docs)
    Route::get('/panduan', function () {
        return view('panduan.index');
    })->name('panduan.index');
    Route::get('/docs', function () {
        return redirect()->route('panduan.index');
    });

    Route::middleware('role:superadmin,admin_bendahara')->group(function () {
        // Struktur Panitia Inti (FULL CRUD)
        Route::resource('struktur', StrukturController::class)->except(['create', 'show', 'edit']);

        // Pemasukan & Pengeluaran (FULL CRUD)
        Route::resource('pemasukan', PemasukanController::class)->except(['create', 'show', 'edit']);
        Route::resource('pengeluaran', PengeluaranController::class)->except(['create', 'show', 'edit']);

        // Sponsor & Dokumen (FULL CRUD)
        Route::resource('sponsor', SponsorController::class)->except(['create', 'show', 'edit']);
        Route::post('/sponsor/{sponsor}/confirm-lunas', [SponsorController::class, 'confirmLunas'])->name('sponsor.confirm-lunas');
        Route::resource('dokumen', DokumenController::class)->except(['create', 'show', 'edit']);

        // Jadwal Event & Relasi
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

        // CRUD Nama Acara (Superadmin & Admin Bendahara)
        Route::resource('acara', AcaraController::class)->except(['create', 'show', 'edit']);

        // Export Laporan Keuangan (PDF Print & Excel CSV)
        Route::get('/keuangan/export/print', [KeuanganController::class, 'exportPrint'])->name('keuangan.export.print');
        Route::get('/keuangan/export/excel', [KeuanganController::class, 'exportExcel'])->name('keuangan.export.excel');
        Route::resource('keuangan', KeuanganController::class)->only(['index', 'store', 'destroy']);
    });

    // Anggaran (RAB) & Modal Awal
    Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
    Route::post('/anggaran/modal-awal', [AnggaranController::class, 'updateModalAwal'])->name('anggaran.update-modal');

    // Notifications API / AJAX
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/simulate', [NotificationController::class, 'simulate'])->name('notifications.simulate');

    // CRUD Nama Desa & Manajemen User (Superadmin Only)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/desa', [DesaController::class, 'index'])->name('desa.index');
        Route::post('/desa', [DesaController::class, 'store'])->name('desa.store');
        Route::put('/desa/{desa}', [DesaController::class, 'update'])->name('desa.update');
        Route::delete('/desa/{desa}', [DesaController::class, 'destroy'])->name('desa.destroy');

        Route::resource('user', UserController::class)->except(['create', 'show', 'edit']);
    });

    // Profile Routes (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
