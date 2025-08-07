<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Simpanan;
use Carbon\Carbon;

class TambahSimpananWajib extends Command
{
    protected $signature = 'simpanan:wajib';
    protected $description = 'Menambahkan simpanan wajib bulanan untuk semua anggota yang belum membayar di bulan ini.';

    public function handle()
    {
        $anggota = User::where('role', 'anggota')->get();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $anggotaDitambahkan = 0;

        foreach ($anggota as $user) {
            // Cek apakah anggota sudah membayar simpanan wajib bulan ini
            $sudahBayar = Simpanan::where('user_id', $user->id)
                                ->where('jenis_simpanan', 'Wajib')
                                ->whereMonth('tanggal_transaksi', $bulanIni)
                                ->whereYear('tanggal_transaksi', $tahunIni)
                                ->exists();

            // HANYA JIKA BELUM BAYAR, tambahkan simpanan baru
            if (!$sudahBayar) {
                Simpanan::create([
                    'user_id' => $user->id,
                    'jenis_simpanan' => 'Wajib',
                    'jumlah' => 50000, // Jumlah simpanan wajib
                    'tanggal_transaksi' => Carbon::now(),
                    'keterangan' => 'Simpanan Wajib Bulanan Otomatis',
                    'processed_by' => 1 // Diasumsikan ID 1 adalah Super Admin
                ]);
                $anggotaDitambahkan++;
            }
        }

        $this->info("Simpanan wajib berhasil ditambahkan untuk {$anggotaDitambahkan} anggota.");
    }
}
