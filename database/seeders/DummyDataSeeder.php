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
        $superAdmin = User::updateOrCreate([
            'id_anggota'     => 'ADM001',
        ], [
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
        $admin = User::updateOrCreate([
            'id_anggota'     => 'ADM002',
        ], [
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
        $anggota = User::updateOrCreate([
            'id_anggota'     => 'AGT001',
        ], [
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

        // --- Tambah Anggota Dummy Banyak ---
        $anggotaList = [
            ['id_anggota' => 'AGT011', 'nama' => 'Amy Karmis', 'email' => 'amy.karmis@email.com'],
            ['id_anggota' => 'AGT012', 'nama' => 'Shirone Vieltal', 'email' => 'shirone.vieltal@email.com'],
            ['id_anggota' => 'AGT013', 'nama' => 'Ethella Vieltal', 'email' => 'ethella.vieltal@email.com'],
            ['id_anggota' => 'AGT014', 'nama' => 'Neid Vieltal', 'email' => 'neid.vieltal@email.com'],
            ['id_anggota' => 'AGT015', 'nama' => 'Claudia Levias', 'email' => 'claudia.levias@email.com'],
            ['id_anggota' => 'AGT016', 'nama' => 'Frey Duran', 'email' => 'frey.duran@email.com'],
            ['id_anggota' => 'AGT017', 'nama' => 'Eileen L. Vieltal', 'email' => 'eileen.vieltal@email.com'],
            ['id_anggota' => 'AGT018', 'nama' => 'Ian Wiggins', 'email' => 'ian.wiggins@email.com'],
            ['id_anggota' => 'AGT019', 'nama' => 'Kazen Lucien', 'email' => 'kazen.lucien@email.com'],
            ['id_anggota' => 'AGT020', 'nama' => 'Louise Vizerion', 'email' => 'louise.vizerion@email.com'],
            ['id_anggota' => 'AGT021', 'nama' => 'Polaris Kant', 'email' => 'polaris.kant@email.com'],
            ['id_anggota' => 'AGT022', 'nama' => 'Rudger Chelici', 'email' => 'rudger.chelici@email.com'],
            ['id_anggota' => 'AGT023', 'nama' => 'Oliver Cheong', 'email' => 'oliver.cheong@email.com'],
            ['id_anggota' => 'AGT024', 'nama' => 'Luna El Haz', 'email' => 'luna.haz@email.com'],
            ['id_anggota' => 'AGT025', 'nama' => 'Alice von Schemer', 'email' => 'alice.schemer@email.com'],
            ['id_anggota' => 'AGT026', 'nama' => 'Deculein Jo', 'email' => 'deculein.jo@email.com'],
            ['id_anggota' => 'AGT027', 'nama' => 'Kiria Lebrace', 'email' => 'kiria.lebrace@email.com'],
            ['id_anggota' => 'AGT028', 'nama' => 'Delphine Harriet', 'email' => 'delphine.harriet@email.com'],
            ['id_anggota' => 'AGT029', 'nama' => 'Kayneth Walker', 'email' => 'kayneth.walker@email.com'],
            ['id_anggota' => 'AGT030', 'nama' => 'Jin Sahyuk', 'email' => 'jin.sahyuk@email.com'],
            ['id_anggota' => 'AGT031', 'nama' => 'Kim Hajin', 'email' => 'kim.hajin@email.com'],
            ['id_anggota' => 'AGT032', 'nama' => 'Chae Nayun', 'email' => 'chae.nayun@email.com'],
            ['id_anggota' => 'AGT033', 'nama' => 'Yoo Yeonha', 'email' => 'yoo.yeonha@email.com'],
            ['id_anggota' => 'AGT034', 'nama' => 'Rachel Whitefall', 'email' => 'rachel.whitefall@email.com'],
            ['id_anggota' => 'AGT035', 'nama' => 'Kim Suho', 'email' => 'kim.suho@email.com'],
            ['id_anggota' => 'AGT036', 'nama' => 'Shin Jonghak', 'email' => 'shin.jonghak@email.com'],
            ['id_anggota' => 'AGT037', 'nama' => 'Oh Sangwoo', 'email' => 'oh.sangwoo@email.com'],
            ['id_anggota' => 'AGT038', 'nama' => 'Joo Yerin', 'email' => 'joo.yerin@email.com'],
            ['id_anggota' => 'AGT039', 'nama' => 'Evandel Whitemoon', 'email' => 'evandel.whitemoon@email.com'],
            ['id_anggota' => 'AGT040', 'nama' => 'Droon Radix', 'email' => 'droon.radix@email.com'],
            ['id_anggota' => 'AGT041', 'nama' => 'Julius Rein', 'email' => 'julius.rein@email.com'],
            ['id_anggota' => 'AGT042', 'nama' => 'Keira Astrea', 'email' => 'keira.astrea@email.com'],
            ['id_anggota' => 'AGT043', 'nama' => 'Reed Adelheid', 'email' => 'reed.adelheid@email.com'],
            ['id_anggota' => 'AGT044', 'nama' => 'Sylvia Evangeline', 'email' => 'sylvia.evangeline@email.com'],
            ['id_anggota' => 'AGT045', 'nama' => 'Irene Holtene', 'email' => 'irene.holtene@email.com'],
            ['id_anggota' => 'AGT046', 'nama' => 'Lucius Kaltein', 'email' => 'lucius.kaltein@email.com'],
            ['id_anggota' => 'AGT047', 'nama' => 'Zeke Barris', 'email' => 'zeke.barris@email.com'],
            ['id_anggota' => 'AGT048', 'nama' => 'Sophia Reinsworth', 'email' => 'sophia.reinsworth@email.com'],
            ['id_anggota' => 'AGT049', 'nama' => 'Arin Tersius', 'email' => 'arin.tersius@email.com'],
            ['id_anggota' => 'AGT050', 'nama' => 'Vera Morgause', 'email' => 'vera.morgause@email.com'],
        ];

        foreach ($anggotaList as $data) {
            User::updateOrCreate(
                ['id_anggota' => $data['id_anggota']],
                [
                    'nama'            => $data['nama'],
                    'email'           => $data['email'],
                    'password'        => Hash::make('password'), // default password
                    'role'            => 'anggota',
                    'alamat'          => 'Jalan Fiksi No. ' . rand(10, 99),
                    'instansi'        => collect(['SMP', 'SMA', 'SMK'])->random(),
                    'no_telp'         => '08' . rand(111111111, 999999999),
                    'tahun_gabung'    => rand(2019, 2024),
                ]
            );
        }
    }
}
