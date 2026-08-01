<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panitias', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('divisi')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->default('Aktif'); // Aktif, Sibuk
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sponsor');
            $table->string('paket')->default('Gold');
            $table->decimal('nominal_proposal', 15, 2)->default(0);
            $table->string('divisi_pj')->nullable();
            $table->string('status')->default('prospek'); // prospek, dikirim, negosiasi, disetujui, lunas
            $table->date('tanggal_update')->nullable();
            $table->timestamps();
        });

        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('kategori'); // Proposal, Laporan Keuangan, Surat Izin, LPJ
            $table->string('tipe_file')->default('PDF');
            $table->string('ukuran_file')->default('1.2 MB');
            $table->string('status')->default('Approved'); // Approved, Pending, Revision
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
        Schema::dropIfExists('sponsors');
        Schema::dropIfExists('panitias');
    }
};
