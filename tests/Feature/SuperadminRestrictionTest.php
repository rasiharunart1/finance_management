<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Desa;
use App\Models\Acara;
use App\Models\TransaksiKeuangan;

class SuperadminRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_superadmin_cannot_update_modal_awal()
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($superadmin)->post(route('anggaran.update-modal'), [
            'modal_awal' => 5000000,
        ]);

        $response->assertRedirect(route('anggaran.index'));
        $response->assertSessionHas('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mengubah modal awal.');
    }

    public function test_superadmin_cannot_create_or_delete_pemasukan()
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $desa = Desa::create([
            'kode_desa' => 'DESA-01',
            'nama_desa' => 'Desa Contoh',
            'kecamatan' => 'Kecamatan Contoh',
        ]);

        $acara = Acara::create([
            'nama_acara' => 'Acara Contoh',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-02',
            'desa_id' => $desa->id,
            'anggaran_rab' => 1000000,
            'status' => 'persiapan',
        ]);

        $responseStore = $this->actingAs($superadmin)->post(route('pemasukan.store'), [
            'acara_id' => $acara->id,
            'jumlah' => 1000000,
            'tanggal_transaksi' => '2026-08-01',
            'kategori' => 'Sponsor / Donatur',
            'keterangan' => 'Tes sponsor',
        ]);

        $responseStore->assertRedirect(route('pemasukan.index'));
        $responseStore->assertSessionHas('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mencatat pemasukan.');

        $trx = TransaksiKeuangan::create([
            'acara_id' => $acara->id,
            'user_id' => $superadmin->id,
            'tipe' => 'pemasukan',
            'jumlah' => 500000,
            'tanggal_transaksi' => '2026-08-01',
            'kategori' => 'Sponsor / Donatur',
            'keterangan' => 'Tes',
            'nomor_transaksi' => 'IN-TEST01',
        ]);

        $responseDestroy = $this->actingAs($superadmin)->delete(route('pemasukan.destroy', $trx));
        $responseDestroy->assertRedirect(route('pemasukan.index'));
        $responseDestroy->assertSessionHas('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak menghapus data pemasukan.');
    }

    public function test_superadmin_cannot_create_or_delete_pengeluaran()
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $desa = Desa::create([
            'kode_desa' => 'DESA-02',
            'nama_desa' => 'Desa Contoh 2',
            'kecamatan' => 'Kecamatan Contoh',
        ]);

        $acara = Acara::create([
            'nama_acara' => 'Acara Contoh',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-02',
            'desa_id' => $desa->id,
            'anggaran_rab' => 1000000,
            'status' => 'persiapan',
        ]);

        $responseStore = $this->actingAs($superadmin)->post(route('pengeluaran.store'), [
            'acara_id' => $acara->id,
            'jumlah' => 500000,
            'tanggal_transaksi' => '2026-08-01',
            'kategori' => 'Operasional / Hadiah',
            'keterangan' => 'Tes pengeluaran',
        ]);

        $responseStore->assertRedirect(route('pengeluaran.index'));
        $responseStore->assertSessionHas('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak mencatat pengeluaran.');

        $trx = TransaksiKeuangan::create([
            'acara_id' => $acara->id,
            'user_id' => $superadmin->id,
            'tipe' => 'pengeluaran',
            'jumlah' => 250000,
            'tanggal_transaksi' => '2026-08-01',
            'kategori' => 'Operasional / Hadiah',
            'keterangan' => 'Tes keluar',
            'nomor_transaksi' => 'OUT-TEST01',
        ]);

        $responseDestroy = $this->actingAs($superadmin)->delete(route('pengeluaran.destroy', $trx));
        $responseDestroy->assertRedirect(route('pengeluaran.index'));
        $responseDestroy->assertSessionHas('error', 'Superadmin hanya bertugas memantau instansi dan tidak berhak menghapus data pengeluaran.');
    }
}
