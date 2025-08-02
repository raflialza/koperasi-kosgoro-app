<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Simpanan;
use Carbon\Carbon;

class TambahSimpananWajib extends Command
{
    /**
     * Nama dan signature dari command.
     * @var string
     */
    protected $signature = 'koperasi:tambah-simpanan-wajib';

    /**
     * Deskripsi dari command.
     * @var string
     */
    protected $description = 'Menambahkan catatan Simpanan Wajib bulanan untuk semua anggota aktif';

    /**
     * Jalankan logika command.
     */
    public function handle()
    {
        $this->info('Memulai proses penambahan Simpanan Wajib...');

        // Ambil semua pengguna dengan role 'anggota'
        $anggota = User::where('role', 'anggota')->get();
        $bulanIni = Carbon::now()->startOfMonth();
        $jumlahSimpananWajib = 100000; // Tentukan jumlah simpanan wajib di sini

        foreach ($anggota as $user) {
            // Cek apakah anggota ini sudah memiliki simpanan wajib untuk bulan ini
            $sudahAda = Simpanan::where('user_id', $user->id)
                                ->where('jenis_simpanan', 'Wajib')
                                ->whereYear('tanggal_transaksi', $bulanIni->year)
                                ->whereMonth('tanggal_transaksi', $bulanIni->month)
                                ->exists();

            // Jika belum ada, buat catatan baru
            if (!$sudahAda) {
                Simpanan::create([
                    'user_id'           => $user->id,
                    'jenis_simpanan'    => 'Wajib',
                    'jumlah'            => $jumlahSimpananWajib,
                    'tanggal_transaksi' => $bulanIni,
                    'processed_by'      => 1, // Diasumsikan diproses oleh Super Admin (ID 1)
                ]);
                $this->info("Simpanan Wajib ditambahkan untuk anggota: {$user->nama}");
            } else {
                $this->comment("Simpanan Wajib untuk {$user->nama} di bulan ini sudah ada.");
            }
        }

        $this->info('Proses selesai.');
        return 0;
    }
}