<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_desa', 50)->unique();
            $table->string('nama_desa');
            $table->string('kecamatan')->default('Kecamatan Horizon');
            $table->string('kepala_desa')->nullable();
            $table->string('kontak', 50)->nullable();
            $table->integer('populasi')->default(0);
            $table->string('status')->default('aktif'); // 'aktif', 'nonaktif'
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_acara');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->decimal('anggaran_rencana', 15, 2)->default(0);
            $table->string('status')->default('planned'); // 'planned', 'ongoing', 'completed', 'cancelled'
            $table->timestamps();
        });

        Schema::create('transaksi_keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')->constrained('acaras')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor_transaksi', 50)->unique();
            $table->string('tipe'); // 'pemasukan', 'pengeluaran'
            $table->string('kategori')->default('Operasional');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->date('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->string('bukti_file')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('transaksi_keuangans');
        Schema::dropIfExists('acaras');
        Schema::dropIfExists('desas');
    }
};
