<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panitia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'divisi',
        'keterangan',
        'status',
        'avatar',
        'phone',
        'user_id',
        'desa_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
