<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Desa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_desa',
        'nama_desa',
        'kecamatan',
        'kepala_desa',
        'kontak',
        'populasi',
        'modal_awal',
        'status',
        'catatan',
    ];

    public function acaras()
    {
        return $this->hasMany(Acara::class);
    }

    public function bendaharas()
    {
        return $this->hasMany(User::class, 'desa_id')->where('role', 'admin_bendahara');
    }

    public function panitias()
    {
        return $this->hasMany(Panitia::class);
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }
}
