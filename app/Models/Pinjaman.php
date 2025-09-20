<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'user_id',
        'jumlah_pinjaman',
        'margin',           // 'interest' diubah menjadi 'margin'
        'tenor',
        'status',
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'keterangan',       // 'keperluan' diubah menjadi 'keterangan'
        'approved_by'
    ];

    /**
     * Atribut yang harus di-cast (diubah tipenya).
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_disetujui' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class);
    }
}