<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Desa;
use App\Models\Acara;
use App\Models\TransaksiKeuangan;
use App\Models\ActivityLog;
use App\Models\Panitia;
use App\Models\Sponsor;
use App\Models\Dokumen;
use App\Notifications\SystemNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users (Superadmin & Bendahara)
        $superadmin = User::firstOrCreate([
            'email' => 'superadmin@nhfinance.id',
        ], [
            'name' => 'Ahmad Superadmin',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $bendahara = User::firstOrCreate([
            'email' => 'bendahara@nhfinance.id',
        ], [
            'name' => 'Budi Santoso',
            'password' => Hash::make('password'),
            'role' => 'admin_bendahara',
            'phone' => '081987654321',
            'is_active' => true,
        ]);

        // 2. Create 10 Desas
        if (Desa::count() === 0) {
            $desasData = [
                ['kode_desa' => 'DS-001', 'nama_desa' => 'Desa Makmur Jaya', 'kecamatan' => 'Kecamatan Horizon', 'kepala_desa' => 'Bpk. Hendra Gunawan', 'kontak' => '081211112222', 'populasi' => 4500, 'status' => 'aktif'],
                ['kode_desa' => 'DS-002', 'nama_desa' => 'Desa Karanganyar', 'kecamatan' => 'Kecamatan Horizon', 'kepala_desa' => 'Ibu Siti Aisyah', 'kontak' => '081233334444', 'populasi' => 3200, 'status' => 'aktif'],
                ['kode_desa' => 'DS-003', 'nama_desa' => 'Desa Mekarsari', 'kecamatan' => 'Kecamatan Nusantara', 'kepala_desa' => 'Bpk. Ahmad Fauzi', 'kontak' => '081255556666', 'populasi' => 5100, 'status' => 'aktif'],
                ['kode_desa' => 'DS-004', 'nama_desa' => 'Desa Sindangsari', 'kecamatan' => 'Kecamatan Nusantara', 'kepala_desa' => 'Bpk. Dedi Kurniawan', 'kontak' => '081277778888', 'populasi' => 2800, 'status' => 'aktif'],
                ['kode_desa' => 'DS-005', 'nama_desa' => 'Desa Sumberjaya', 'kecamatan' => 'Kecamatan Makmur', 'kepala_desa' => 'Bpk. Bambang Pamungkas', 'kontak' => '081299990000', 'populasi' => 3900, 'status' => 'aktif'],
                ['kode_desa' => 'DS-006', 'nama_desa' => 'Desa Harapan Mulya', 'kecamatan' => 'Kecamatan Makmur', 'kepala_desa' => 'Ibu Ratna Sari', 'kontak' => '081311112222', 'populasi' => 4100, 'status' => 'aktif'],
                ['kode_desa' => 'DS-007', 'nama_desa' => 'Desa Cibatu Asri', 'kecamatan' => 'Kecamatan Permai', 'kepala_desa' => 'Bpk. Heriyanto', 'kontak' => '081333334444', 'populasi' => 2500, 'status' => 'aktif'],
                ['kode_desa' => 'DS-008', 'nama_desa' => 'Desa Sukawangi', 'kecamatan' => 'Kecamatan Permai', 'kepala_desa' => 'Bpk. Yudi Pratama', 'kontak' => '081355556666', 'populasi' => 3600, 'status' => 'aktif'],
                ['kode_desa' => 'DS-009', 'nama_desa' => 'Desa Cipta Karya', 'kecamatan' => 'Kecamatan Horizon', 'kepala_desa' => 'Bpk. Eko Prasetyo', 'kontak' => '081377778888', 'populasi' => 4800, 'status' => 'aktif'],
                ['kode_desa' => 'DS-010', 'nama_desa' => 'Desa Giri Mukti', 'kecamatan' => 'Kecamatan Makmur', 'kepala_desa' => 'Ibu Nurhayati', 'kontak' => '081399990000', 'populasi' => 3100, 'status' => 'aktif'],
            ];

            foreach ($desasData as $data) {
                Desa::create($data);
            }
        }

        $desas = Desa::all();

        // 3. Create 15 Acaras / Event
        if (Acara::count() === 0) {
            $acarasData = [
                ['nama_acara' => 'Pesta Rakyat & Festival Kegiatan', 'desa_index' => 0, 'anggaran' => 75000000, 'status' => 'ongoing', 'tgl' => '2026-08-17 08:00:00'],
                ['nama_acara' => 'Musyawarah Pembangunan Desa', 'desa_index' => 1, 'anggaran' => 25000000, 'status' => 'planned', 'tgl' => '2026-08-20 09:00:00'],
                ['nama_acara' => 'Pelatihan UMKM Digital', 'desa_index' => 2, 'anggaran' => 35000000, 'status' => 'completed', 'tgl' => '2026-07-10 08:30:00'],
                ['nama_acara' => 'Turnamen Sepak Bola Desa', 'desa_index' => 0, 'anggaran' => 40000000, 'status' => 'planned', 'tgl' => '2026-09-01 15:00:00'],
                ['nama_acara' => 'Pemeriksaan Kesehatan Gratis', 'desa_index' => 3, 'anggaran' => 18000000, 'status' => 'completed', 'tgl' => '2026-07-05 08:00:00'],
                ['nama_acara' => 'Pesta Rakyat Kemerdekaan', 'desa_index' => 4, 'anggaran' => 60000000, 'status' => 'ongoing', 'tgl' => '2026-08-17 07:00:00'],
                ['nama_acara' => 'Penyuluhan Pertanian Organik', 'desa_index' => 5, 'anggaran' => 22000000, 'status' => 'planned', 'tgl' => '2026-08-25 09:30:00'],
                ['nama_acara' => 'Lokakarya Pengelolaan Sampah', 'desa_index' => 6, 'anggaran' => 15000000, 'status' => 'completed', 'tgl' => '2026-06-20 09:00:00'],
                ['nama_acara' => 'Pasar Murah Bersubsidi', 'desa_index' => 7, 'anggaran' => 50000000, 'status' => 'ongoing', 'tgl' => '2026-08-01 07:30:00'],
                ['nama_acara' => 'Rembuk Stunting dan Gizi Anak', 'desa_index' => 8, 'anggaran' => 20000000, 'status' => 'planned', 'tgl' => '2026-08-30 08:00:00'],
                ['nama_acara' => 'Bazar Kuliner Desa', 'desa_index' => 9, 'anggaran' => 30000000, 'status' => 'planned', 'tgl' => '2026-09-10 09:00:00'],
                ['nama_acara' => 'Sosialisasi Siaga Bencana', 'desa_index' => 1, 'anggaran' => 16000000, 'status' => 'completed', 'tgl' => '2026-07-15 08:30:00'],
                ['nama_acara' => 'Pengembangan BUMDes', 'desa_index' => 2, 'anggaran' => 45000000, 'status' => 'ongoing', 'tgl' => '2026-08-10 10:00:00'],
                ['nama_acara' => 'Gotong Royong Bersih Sungai', 'desa_index' => 3, 'anggaran' => 12000000, 'status' => 'completed', 'tgl' => '2026-07-22 07:00:00'],
                ['nama_acara' => 'Festival Musik Anak Muda', 'desa_index' => 4, 'anggaran' => 55000000, 'status' => 'planned', 'tgl' => '2026-09-20 19:00:00'],
            ];

            foreach ($acarasData as $item) {
                Acara::create([
                    'desa_id' => $desas[$item['desa_index']]->id,
                    'user_id' => $superadmin->id,
                    'nama_acara' => $item['nama_acara'],
                    'deskripsi' => 'Kegiatan perayaan dan penguatan kelembagaan desa di ' . $desas[$item['desa_index']]->nama_desa,
                    'lokasi' => 'Balai ' . $desas[$item['desa_index']]->nama_desa,
                    'tanggal_mulai' => $item['tgl'],
                    'tanggal_selesai' => date('Y-m-d H:i:s', strtotime($item['tgl'] . ' +1 day')),
                    'anggaran_rencana' => $item['anggaran'],
                    'status' => $item['status'],
                ]);
            }
        }

        $acaras = Acara::all();

        // 4. Create Transaksi Keuangan
        if (TransaksiKeuangan::count() === 0) {
            $transaksis = [
                ['acara_idx' => 0, 'tipe' => 'pemasukan', 'jumlah' => 25000000, 'ket' => 'PT. Indofood Sukses (Sponsorship Utama)'],
                ['acara_idx' => 0, 'tipe' => 'pemasukan', 'jumlah' => 12500000, 'ket' => 'Iuran Warga RT 01 - RT 05 (Kolektif)'],
                ['acara_idx' => 0, 'tipe' => 'pemasukan', 'jumlah' => 5000000, 'ket' => 'Hamba Allah (Donasi Pribadi)'],
                ['acara_idx' => 0, 'tipe' => 'pengeluaran', 'jumlah' => 15000000, 'ket' => 'Sewa Panggung & Lighting Acara'],
                ['acara_idx' => 0, 'tipe' => 'pengeluaran', 'jumlah' => 8500000, 'ket' => 'Hadiah Lomba & Trofi Juara'],
                ['acara_idx' => 2, 'tipe' => 'pemasukan', 'jumlah' => 35000000, 'ket' => 'Alokasi Dana Swadaya'],
                ['acara_idx' => 2, 'tipe' => 'pengeluaran', 'jumlah' => 12000000, 'ket' => 'Konsumsi & Akomodasi Peserta'],
                ['acara_idx' => 5, 'tipe' => 'pemasukan', 'jumlah' => 60000000, 'ket' => 'Dana Hibah Kemerdekaan'],
                ['acara_idx' => 5, 'tipe' => 'pengeluaran', 'jumlah' => 18000000, 'ket' => 'Perlengkapan Lomba & Hadiah'],
                ['acara_idx' => 8, 'tipe' => 'pemasukan', 'jumlah' => 50000000, 'ket' => 'Subsidi Pemerintah Daerah'],
                ['acara_idx' => 8, 'tipe' => 'pengeluaran', 'jumlah' => 22000000, 'ket' => 'Pengadaan Sembako Pasar Murah'],
            ];

            foreach ($transaksis as $i => $trx) {
                TransaksiKeuangan::create([
                    'acara_id' => $acaras[$trx['acara_idx']]->id,
                    'user_id' => $bendahara->id,
                    'nomor_transaksi' => 'TRX-' . (8800 + $i),
                    'tipe' => $trx['tipe'],
                    'kategori' => $trx['tipe'] === 'pemasukan' ? 'Sponsor / Iuran' : 'Operasional / Hadiah',
                    'jumlah' => $trx['jumlah'],
                    'tanggal_transaksi' => date('Y-m-d', strtotime('-' . (10 - $i) . ' days')),
                    'keterangan' => $trx['ket'],
                ]);
            }
        }

        // 5. Assign Desa to default Bendahara (hasOne Desa)
        if ($desas->count() > 0 && !$bendahara->desa_id) {
            $bendahara->update(['desa_id' => $desas[0]->id]);
        }

        // 6. Seed Panitia (Struktur Panitia Inti)
        if (Panitia::count() === 0) {
            $desaId = $desas[0]->id ?? 1;
            Panitia::create(['desa_id' => $desaId, 'nama' => 'Andi Nugroho', 'jabatan' => 'Ketua Panitia', 'divisi' => 'Pimpinan Inti', 'keterangan' => 'Koordinator Umum / Ketua Pelaksana', 'status' => 'Aktif', 'avatar' => 'AN', 'phone' => '081234111222']);
            Panitia::create(['desa_id' => $desaId, 'nama' => 'Siti Aminah', 'jabatan' => 'Wakil Ketua', 'divisi' => 'Pimpinan Inti', 'keterangan' => 'Pengawas Lapangan & Lomba', 'status' => 'Aktif', 'avatar' => 'SA', 'phone' => '081234222333']);
            Panitia::create(['desa_id' => $desaId, 'nama' => 'Rina Wati', 'jabatan' => 'Sekretaris', 'divisi' => 'Kesekretariatan', 'keterangan' => 'Administrasi & Proposal', 'status' => 'Aktif', 'avatar' => 'RW', 'phone' => '081234333444']);
            Panitia::create(['desa_id' => $desaId, 'nama' => 'Budi Santoso', 'jabatan' => 'Bendahara Utama', 'divisi' => 'Keuangan', 'keterangan' => 'Pengelola Kas & Pembukuan', 'status' => 'Aktif', 'avatar' => 'BS', 'phone' => '081234444555']);
            Panitia::create(['desa_id' => $desaId, 'nama' => 'Dimas & Tim (5 Orang)', 'jabatan' => 'Divisi Acara', 'divisi' => 'Acara & Hiburan', 'keterangan' => 'Pelaksanaan Lomba & Panggung', 'status' => 'Sibuk', 'avatar' => 'DA', 'phone' => '081234555666']);
            Panitia::create(['desa_id' => $desaId, 'nama' => 'Haji Lulung & Tim', 'jabatan' => 'Divisi Keamanan & Humas', 'divisi' => 'Humas & Keamanan', 'keterangan' => 'Koordinasi Warga & RT/RW', 'status' => 'Aktif', 'avatar' => 'HL', 'phone' => '081234666777']);
        }

        // 7. Seed Sponsor (Pipeline Sponsorship)
        if (Sponsor::count() === 0) {
            $desaId = $desas[0]->id ?? 1;
            Sponsor::create(['desa_id' => $desaId, 'nama_sponsor' => 'PT. Indofood Sukses Makmur', 'paket' => 'Platinum', 'nominal_proposal' => 10000000, 'divisi_pj' => 'Div. Humas & Sponsor', 'status' => 'lunas', 'tanggal_update' => now()->subDays(2)]);
            Sponsor::create(['desa_id' => $desaId, 'nama_sponsor' => 'Bank BRI Cabang Horizon', 'paket' => 'Platinum', 'nominal_proposal' => 5000000, 'divisi_pj' => 'Div. Humas', 'status' => 'negosiasi', 'tanggal_update' => now()->subDays(1)]);
            Sponsor::create(['desa_id' => $desaId, 'nama_sponsor' => 'Dealer Honda Mandiri', 'paket' => 'Gold', 'nominal_proposal' => 3000000, 'divisi_pj' => 'Div. Humas', 'status' => 'dikirim', 'tanggal_update' => now()->subDays(3)]);
            Sponsor::create(['desa_id' => $desaId, 'nama_sponsor' => 'Toko Bangunan Jaya Mandiri', 'paket' => 'Silver', 'nominal_proposal' => 1500000, 'divisi_pj' => 'Div. Perlengkapan', 'status' => 'disetujui', 'tanggal_update' => now()]);
            Sponsor::create(['desa_id' => $desaId, 'nama_sponsor' => 'CV. Agro Nusantara', 'paket' => 'Gold', 'nominal_proposal' => 4000000, 'divisi_pj' => 'Div. Sponsor', 'status' => 'prospek', 'tanggal_update' => now()->subDays(4)]);
        }

        // 8. Seed Dokumen & Laporan
        if (Dokumen::count() === 0) {
            $desaId = $desas[0]->id ?? 1;
            Dokumen::create(['desa_id' => $desaId, 'nama_dokumen' => 'Proposal Resmi Kegiatan & Event.pdf', 'kategori' => 'Proposal', 'tipe_file' => 'PDF', 'ukuran_file' => '2.4 MB', 'status' => 'Approved']);
            Dokumen::create(['desa_id' => $desaId, 'nama_dokumen' => 'RAB Lengkap Operasional Kegiatan.xlsx', 'kategori' => 'Laporan Keuangan', 'tipe_file' => 'XLSX', 'ukuran_file' => '1.1 MB', 'status' => 'Approved']);
            Dokumen::create(['desa_id' => $desaId, 'nama_dokumen' => 'Surat Izin Keramaian Polsek.pdf', 'kategori' => 'Surat Izin', 'tipe_file' => 'PDF', 'ukuran_file' => '0.8 MB', 'status' => 'Approved']);
            Dokumen::create(['desa_id' => $desaId, 'nama_dokumen' => 'Laporan Pertanggungjawaban (LPJ) Sementara.docx', 'kategori' => 'LPJ', 'tipe_file' => 'DOCX', 'ukuran_file' => '1.7 MB', 'status' => 'Revision']);
            Dokumen::create(['desa_id' => $desaId, 'nama_dokumen' => 'Surat Undangan Pimpinan.pdf', 'kategori' => 'Surat Izin', 'tipe_file' => 'PDF', 'ukuran_file' => '0.5 MB', 'status' => 'Approved']);
        }
    }
}
