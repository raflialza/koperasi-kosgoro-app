<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id_anggota',
        'nama',
        'email',
        'password',
        'role',
        'no_telp',
        'alamat',
        'instansi',
        'tahun_gabung',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the route key for the model.
     * Ini memberitahu Laravel untuk menggunakan kolom 'id_anggota'
     * saat mencari User dari URL, contoh: /anggota/AGT001/edit
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id_anggota';
    }

    // Helper methods untuk role
    public function isAnggota()
    {
        return $this->role === 'anggota';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function approvedPinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'approved_by');
    }

    public function simpanan()
    {
        return $this->hasMany(Simpanan::class);
    }

    public function processedSimpanan()
    {
        return $this->hasMany(Simpanan::class, 'processed_by');
    }
}
