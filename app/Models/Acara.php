<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acara extends Model
{
    use HasFactory;

    protected $fillable = [
        'desa_id',
        'user_id',
        'nama_acara',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'anggaran_rencana',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'anggaran_rencana' => 'decimal:2',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksis()
    {
        return $this->hasMany(TransaksiKeuangan::class);
    }

    public function getTotalPengeluaranAttribute(): float
    {
        return (float) $this->transaksis()->where('tipe', 'pengeluaran')->sum('jumlah');
    }

    public function getTotalPemasukanAttribute(): float
    {
        return (float) $this->transaksis()->where('tipe', 'pemasukan')->sum('jumlah');
    }

    public function getPersentaseAnggaranAttribute(): int
    {
        if ($this->anggaran_rencana <= 0) {
            return 0;
        }
        $persen = ($this->total_pengeluaran / $this->anggaran_rencana) * 100;
        return (int) min(100, round($persen));
    }
}
