<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'desa_id',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'admin_bendahara';
    }

    public function isAnggotaPanitia(): bool
    {
        return $this->role === 'anggota_panitia';
    }

    public function acaras()
    {
        return $this->hasMany(Acara::class);
    }

    public function transaksiKeuangans()
    {
        return $this->hasMany(TransaksiKeuangan::class);
    }
}
