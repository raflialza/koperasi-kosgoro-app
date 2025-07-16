<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- Create Super Admin ---
        $superAdmin = User::create([
            'id_anggota'     => 'ADM001',
            'nama'           => 'Super Admin',
            'email'          => 'superadmin@email.com',
            'password'       => Hash::make('password'),
            'role'           => 'super_admin',
            'no_telp'        => '081234567890',
            'alamat'         => 'Jl. Admin Super No. 1',
            'instansi'       => 'SMK',
            'tahun_gabung'   => now()->year,
        ]);

        // --- Create Admin ---
        $admin = User::create([
            'id_anggota'     => 'ADM002',
            'nama'           => 'Admin Biasa',
            'email'          => 'admin@email.com',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'no_telp'        => '081234567891',
            'alamat'         => 'Jl. Admin Biasa No. 2',
            'instansi'       => 'SMA',
            'tahun_gabung'   => now()->year,
        ]);

        // --- Create Anggota ---
        $anggota = User::create([
            'id_anggota'     => 'AGT001',
            'nama'           => 'Anggota Satu',
            'email'          => 'anggota@email.com',
            'password'       => Hash::make('password'),
            'role'           => 'anggota',
            'no_telp'        => '081234567892',
            'alamat'         => 'Jl. Anggota No. 3',
            'instansi'       => 'SMA',
            'tahun_gabung'   => now()->year - 1,
        ]);

        // --- Simpanan Dummy (12 bulan) ---
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            Simpanan::create([
                'user_id'           => $anggota->id,
                'jenis_simpanan'    => 'wajib',
                'tahun'             => now()->year,
                'bulan'             => $bulan,
                'saldo_awal'        => 500000,
                'jumlah'            => 100000,
                'saldo_akhir'       => 500000 + ($bulan * 100000),
                'tanggal_transaksi' => now()->setMonth($bulan)->startOfMonth(),
                'keterangan'        => 'Simpanan wajib bulan ke-' . $bulan,
                'processed_by'      => $admin->id,
            ]);
        }

        // --- Pinjaman Dummy ---
        Pinjaman::create([
            'user_id'             => $anggota->id,
            'tahun'               => now()->year,
            'saldo_pinjaman_awal'=> 0,
            'jumlah_pinjaman'     => 2000000,
            'bunga'               => 5.00,
            'jangka_waktu'        => 12,
            'status'              => 'approved',
            'tanggal_pinjam'      => now()->subMonths(1),
            'tanggal_jatuh_tempo' => now()->addMonths(11),
            'keperluan'           => 'Modal usaha kecil',
            'approved_by'         => $admin->id,
            'approved_at'         => now(),
        ]);
    }
}
