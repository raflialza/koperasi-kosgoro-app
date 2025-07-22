<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran';

    protected $fillable = [
        'pinjaman_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'angsuran_ke',
        'processed_by',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}