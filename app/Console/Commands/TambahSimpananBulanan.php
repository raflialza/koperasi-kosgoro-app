<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\Setting;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TambahSimpananBulanan extends Command
{
    /**
     * Nama dan signature dari perintah konsol.
     *
     * @var string
     */
    protected $signature = 'simpanan:bulanan';

    /**
     * Deskripsi dari perintah konsol.
     *
     * @var string
     */
    protected $description = 'Menambahkan simpanan pokok dan wajib secara otomatis untuk semua anggota setiap bulan.';

    /**
     * Jalankan perintah konsol.
     */
    public function handle()
    {
        $this->info('Memulai proses penambahan simpanan pokok dan wajib bulanan...');

        $jumlahPokok = Setting::where('key', 'simpanan_pokok_otomatis')->first()->value ?? 100000;
        $jumlahWajib = Setting::where('key', 'simpanan_wajib_otomatis')->first()->value ?? 50000;
        $this->info("Menggunakan nilai dari database: Pokok (Rp ".number_format($jumlahPokok)."), Wajib (Rp ".number_format($jumlahWajib).")");

        $anggota = User::where('role', 'anggota')->get();

        if ($anggota->isEmpty()) {
            $this->warn('Tidak ada anggota yang ditemukan. Proses dihentikan.');
            return 0;
        }

        $bulanIni = Carbon::now()->startOfMonth();
        $berhasil = 0;

        foreach ($anggota as $user) {
            $sudahAdaSimpanan = Simpanan::where('user_id', $user->id)
                ->whereIn('jenis_simpanan', ['Pokok', 'Wajib'])
                ->where('keterangan', 'Iuran bulanan otomatis')
                ->where('created_at', '>=', $bulanIni)
                ->exists();

            if (!$sudahAdaSimpanan) {
                // Tambahkan Simpanan Pokok
                Simpanan::create([
                    'user_id' => $user->id,
                    'jenis_simpanan' => 'Pokok',
                    'jumlah' => $jumlahPokok,
                    'keterangan' => 'Iuran bulanan otomatis',
                    'tanggal_transaksi' => now(), // PERBAIKAN DI SINI
                ]);

                // Tambahkan Simpanan Wajib
                Simpanan::create([
                    'user_id' => $user->id,
                    'jenis_simpanan' => 'Wajib',
                    'jumlah' => $jumlahWajib,
                    'keterangan' => 'Iuran bulanan otomatis',
                    'tanggal_transaksi' => now(), // PERBAIKAN DI SINI
                ]);

                $this->line("Simpanan pokok & wajib untuk {$user->nama} berhasil ditambahkan.");
                $berhasil++;
            } else {
                $this->line("Simpanan untuk {$user->nama} bulan ini sudah ada, dilewati.");
            }
        }

        $this->info("Proses selesai. Simpanan bulanan berhasil ditambahkan untuk {$berhasil} anggota.");
        return 0;
    }
}

