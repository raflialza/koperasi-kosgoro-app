<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pinjaman;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- Create Super Admin & Admin ---
        User::updateOrCreate(['id_anggota' => 'ADM001'], [
            'nama' => 'Super Admin',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Admin Super No. 1',
            'instansi' => 'SMK',
            'tahun_gabung' => now()->year,
        ]);

        User::updateOrCreate(['id_anggota' => 'ADM002'], [
            'nama' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_telp' => '081234567891',
            'alamat' => 'Jl. Admin No. 2',
            'instansi' => 'SMA',
            'tahun_gabung' => now()->year,
        ]);

        // --- Daftar Anggota dari Manhwa ---
        $anggotaList = [
            // Infinite Mage (6)
            ['nama' => 'Shirone Arian', 'email' => 'shirone.arian@email.com'],
            ['nama' => 'Amy Karmis', 'email' => 'amy.karmis@email.com'],
            ['nama' => 'Rian Ogent', 'email' => 'rian.ogent@email.com'],
            ['nama' => 'Tess Elzaine', 'email' => 'tess.elzaine@email.com'],
            ['nama' => 'Neid', 'email' => 'neid.swordsman@email.com'],
            ['nama' => 'Eruki', 'email' => 'eruki.scholar@email.com'],

            // Pick Me Up (6)
            ['nama' => 'Loki', 'email' => 'loki.master@email.com'],
            ['nama' => 'Syris Agentheim', 'email' => 'syris.agentheim@email.com'],
            ['nama' => 'Yurnet Seed', 'email' => 'yurnet.seed@email.com'],
            ['nama' => 'Nihaku Geistfeld', 'email' => 'nihaku.geistfeld@email.com'],
            ['nama' => 'Ridigion', 'email' => 'ridigion.warrior@email.com'],
            ['nama' => 'Muden Nidelk', 'email' => 'muden.nidelk@email.com'],

            // Magic Academy Genius Blinker (5)
            ['nama' => 'Baek Yu Seol', 'email' => 'baek.yuseol@email.com'],
            ['nama' => 'Hong Bi Yeon', 'email' => 'hong.biyeon@email.com'],
            ['nama' => 'Aiselle Molf', 'email' => 'aiselle.molf@email.com'],
            ['nama' => 'Flame', 'email' => 'flame.spirit@email.com'],
            ['nama' => 'Ma Yu Seong', 'email' => 'ma.yuseong@email.com'],

            // Terminally-ill Genius Dark Knight (5)
            ['nama' => 'Knox Von Reinhaver', 'email' => 'knox.reinhaver@email.com'],
            ['nama' => 'Penelope Von Arkheim', 'email' => 'penelope.arkheim@email.com'],
            ['nama' => 'Thalia Von Steelinor', 'email' => 'thalia.steelinor@email.com'],
            ['nama' => 'Elanor De Rivalin', 'email' => 'elanor.rivalin@email.com'],
            ['nama' => 'Leon Von Marbas', 'email' => 'leon.marbas@email.com'],
            
            // The Extra Academy Survival Guide (6)
            ['nama' => 'Ed Rothtaylor', 'email' => 'ed.rothtaylor@email.com'],
            ['nama' => 'Lortelle Kecheln', 'email' => 'lortelle.kecheln@email.com'],
            ['nama' => 'Lucy Maeril', 'email' => 'lucy.maeril@email.com'],
            ['nama' => 'Janica Faylover', 'email' => 'janica.faylover@email.com'],
            ['nama' => 'Clarice Ecknair', 'email' => 'clarice.ecknair@email.com'],
            ['nama' => 'Zix', 'email' => 'zix.spearman@email.com'],

            // The Novel's Extra (6)
            ['nama' => 'Kim Hajin', 'email' => 'kim.hajin@email.com'],
            ['nama' => 'Kim Suho', 'email' => 'kim.suho@email.com'],
            ['nama' => 'Chae Nayun', 'email' => 'chae.nayun@email.com'],
            ['nama' => 'Yoo Yeonha', 'email' => 'yoo.yeonha@email.com'],
            ['nama' => 'Rachel', 'email' => 'rachel.english@email.com'],
            ['nama' => 'Evangel', 'email' => 'evangel.spirit@email.com'],

            // Lookism (15)
            ['nama' => 'Park Hyung Seok', 'email' => 'park.hyungseok@email.com'],
            ['nama' => 'Kim Gimyung', 'email' => 'kim.gimyung@email.com'],
            ['nama' => 'Lee Zin', 'email' => 'lee.zin@email.com'],
            ['nama' => 'Vasco', 'email' => 'vasco.tabasco@email.com'],
            ['nama' => 'Park Ha Neul', 'email' => 'park.haneul@email.com'],
            ['nama' => 'Jonggun', 'email' => 'jonggun.fighter@email.com'],
            ['nama' => 'Park Se Rim', 'email' => 'park.serim@email.com'],
            ['nama' => 'Jang Hyun', 'email' => 'jang.hyun@email.com'],
            ['nama' => 'Chae Won Seok', 'email' => 'chae.wonseok@email.com'],
            ['nama' => 'Choi Soo-Jung', 'email' => 'choi.soojung@email.com'],
            ['nama' => 'Seong Yo-han', 'email' => 'seong.yohan@email.com'],
            ['nama' => 'Han Shin Woo', 'email' => 'han.shinwoo@email.com'],
            ['nama' => 'Jin Ho Bin', 'email' => 'jin.hobin@email.com'],
            ['nama' => 'Hong Jae Yeol', 'email' => 'hong.jaeyeol@email.com'],
            ['nama' => 'Ahn Hyun Seong', 'email' => 'ahn.hyunseong@email.com'],
        ];

        $idCounter = 1;
        foreach ($anggotaList as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'id_anggota'      => 'AGT' . str_pad($idCounter++, 3, '0', STR_PAD_LEFT),
                    'nama'            => $data['nama'],
                    'password'        => Hash::make('password'),
                    'role'            => 'anggota',
                    'alamat'          => 'Jalan Fiksi No. ' . rand(10, 99),
                    'instansi'        => collect(['SMP', 'SMA', 'SMK'])->random(),
                    'no_telp'         => '08' . rand(111111111, 999999999),
                    'tahun_gabung'    => rand(2020, 2024),
                ]
                
            );
            $this->command->info('Membuat 30 data pengajuan pinjaman acak...');

    // Ambil semua ID anggota yang ada
    $anggotaIds = User::where('role', 'anggota')->pluck('id');

    if ($anggotaIds->isEmpty()) {
        $this->command->error('Tidak ada anggota ditemukan untuk membuat pinjaman.');
        return;
    }

    $keperluanList = ['Renovasi Rumah', 'Biaya Pendidikan', 'Modal Usaha', 'Kebutuhan Mendesak', 'Pembelian Kendaraan'];

    for ($i = 0; $i < 30; $i++) {
        Pinjaman::create([
            'user_id'           => $anggotaIds->random(),
            'jumlah_pinjaman'   => rand(10, 100) * 100000, // Pinjaman antara 1jt - 10jt
            'persentase_bunga'  => 0.10, // Bunga 10%
            'total_tagihan'     => 0, // Akan dihitung ulang nanti
            'tenor'             => collect([6, 12, 18, 24])->random(), // Tenor acak
            'keperluan'         => collect($keperluanList)->random(),
            'tanggal_pengajuan' => now()->subDays(rand(1, 365)), // Tanggal pengajuan acak dalam setahun terakhir
            'status'            => 'menunggu',
        ]);
    }

    // Hitung ulang total_tagihan untuk semua pinjaman yang baru dibuat
    Pinjaman::where('total_tagihan', 0)->where('status', 'menunggu')->cursor()->each(function ($pinjaman) {
        $pokok = $pinjaman->jumlah_pinjaman;
        $bunga = $pinjaman->persentase_bunga;
        $pinjaman->total_tagihan = $pokok + ($pokok * $bunga);
        $pinjaman->save();
    });

    $this->command->info('Selesai membuat data pengajuan pinjaman.');
}
        
    }
}