<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'simpanan';

    protected $fillable = [
        'user_id',
        'jenis_simpanan',
        'tahun',
        'bulan',
        'saldo_awal',
        'jumlah',
        'saldo_akhir',
        'tanggal_transaksi',
        'keterangan',
        'processed_by',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}