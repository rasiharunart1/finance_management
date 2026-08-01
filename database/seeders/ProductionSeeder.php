<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds for clean production environment.
     * ZERO dummy data (no dummy transactions, no dummy sponsors, no dummy panitia).
     */
    public function run(): void
    {
        // 1. Buat 1 Desa Dasar jika belum ada
        $desa = Desa::firstOrCreate(
            ['kode_desa' => '33.01.01.2001'],
            [
                'nama_desa' => 'Desa Pusat (Utama)',
                'kecamatan' => 'Kecamatan Utama',
                'kepala_desa' => 'Kepala Desa / Ketua Panitia',
                'jumlah_warga' => 0,
                'modal_awal' => 0,
            ]
        );

        // 2. Buat Akun Superadmin Utama jika belum ada
        $adminEmail = env('ADMIN_EMAIL', 'superadmin@nhfinance.id');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Superadmin Utama',
                'password' => Hash::make($adminPassword),
                'role' => 'superadmin',
                'desa_id' => $desa->id,
            ]
        );

        $this->command->info('✅ ProductionSeeder sukses! 1 Desa Utama & 1 Superadmin siap digunakan tanpa data dummy.');
    }
}
