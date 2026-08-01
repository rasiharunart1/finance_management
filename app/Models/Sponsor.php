<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sponsor',
        'paket',
        'nominal_proposal',
        'nominal_final',
        'bukti_struk',
        'divisi_pj',
        'status',
        'tanggal_update',
        'desa_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_update' => 'date',
            'nominal_proposal' => 'decimal:2',
            'nominal_final' => 'decimal:2',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
