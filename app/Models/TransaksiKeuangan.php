<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'acara_id',
        'user_id',
        'nomor_transaksi',
        'tipe',
        'kategori',
        'jumlah',
        'tanggal_transaksi',
        'keterangan',
        'bukti_file',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_transaksi' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function acara()
    {
        return $this->belongsTo(Acara::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
