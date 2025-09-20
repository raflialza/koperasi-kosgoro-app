<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pinjaman;
use Carbon\Carbon;
use App\Models\Angsuran;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- Create Super Admin & Admin ---
        $superAdmin = User::updateOrCreate(['id_anggota' => 'ADM001'], [
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

        // --- Daftar Anggota ---
        $anggotaList = [
            ['nama' => 'Anggota', 'email' => 'anggota@email.com'],
            // Infinite Mage (6)
            ['nama' => 'Shirone Arian', 'email' => 'shirone@email.com'],
            ['nama' => 'Amy Karmis', 'email' => 'amy@email.com'],
            ['nama' => 'Rian Ogent', 'email' => 'rian@email.com'],
            ['nama' => 'Tess Elzaine', 'email' => 'tess@email.com'],
            ['nama' => 'Neid', 'email' => 'neid@email.com'],
            ['nama' => 'Eruki', 'email' => 'eruki@email.com'],

            // Pick Me Up (6)
            ['nama' => 'Loki', 'email' => 'loki@email.com'],
            ['nama' => 'Syris Agentheim', 'email' => 'syris@email.com'],
            ['nama' => 'Yurnet Seed', 'email' => 'yurnet@email.com'],
            ['nama' => 'Nihaku Geistfeld', 'email' => 'nihaku@email.com'],
            ['nama' => 'Ridigion', 'email' => 'ridigion@email.com'],
            ['nama' => 'Muden Nidelk', 'email' => 'muden@email.com'],

            // Magic Academy Genius Blinker (5)
            ['nama' => 'Baek Yu Seol', 'email' => 'yuseol@email.com'],
            ['nama' => 'Hong Bi Yeon', 'email' => 'biyeon@email.com'],
            ['nama' => 'Aiselle Molf', 'email' => 'aiselle@email.com'],
            ['nama' => 'Flame', 'email' => 'flame@email.com'],
            ['nama' => 'Ma Yu Seong', 'email' => 'mayuseong@email.com'],

            // Terminally-ill Genius Dark Knight (5)
            ['nama' => 'Knox Von Reinhaver', 'email' => 'knox@email.com'],
            ['nama' => 'Penelope Von Arkheim', 'email' => 'penelope@email.com'],
            ['nama' => 'Thalia Von Steelinor', 'email' => 'thalia@email.com'],
            ['nama' => 'Elanor De Rivalin', 'email' => 'elanor@email.com'],
            ['nama' => 'Leon Von Marbas', 'email' => 'leon@email.com'],

            // The Extra Academy Survival Guide (6)
            ['nama' => 'Ed Rothtaylor', 'email' => 'ed@email.com'],
            ['nama' => 'Lortelle Kecheln', 'email' => 'lortelle@email.com'],
            ['nama' => 'Lucy Maeril', 'email' => 'lucy@email.com'],
            ['nama' => 'Janica Faylover', 'email' => 'janica@email.com'],
            ['nama' => 'Clarice Ecknair', 'email' => 'clarice@email.com'],
            ['nama' => 'Zix', 'email' => 'zix@email.com'],

            // The Novel's Extra (6)
            ['nama' => 'Kim Hajin', 'email' => 'hajin@email.com'],
            ['nama' => 'Kim Suho', 'email' => 'suho@email.com'],
            ['nama' => 'Chae Nayun', 'email' => 'nayun@email.com'],
            ['nama' => 'Yoo Yeonha', 'email' => 'yeonha@email.com'],
            ['nama' => 'Rachel', 'email' => 'rachel@email.com'],
            ['nama' => 'Evangel', 'email' => 'evangel@email.com'],

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
                    'id_anggota'     => 'AGT' . str_pad($idCounter++, 3, '0', STR_PAD_LEFT),
                    'nama'           => $data['nama'],
                    'password'       => Hash::make('password'),
                    'role'           => 'anggota',
                    'alamat'         => 'Jalan Fiksi No. ' . rand(10, 99),
                    'instansi'       => collect(['SMP', 'SMA', 'SMK'])->random(),
                    'no_telp'        => '08' . rand(111111111, 999999999),
                    'tahun_gabung'   => rand(2020, 2024),
                ]
            );
        }

        $this->command->info('Data admin dan anggota berhasil dibuat.');

        $anggotaIds = User::where('role', 'anggota')->pluck('id');
        if ($anggotaIds->isEmpty()) {
            $this->command->error('Tidak ada anggota ditemukan untuk membuat pinjaman.');
            return;
        }

        $keteranganList = ['Renovasi Rumah', 'Biaya Pendidikan', 'Modal Usaha', 'Kebutuhan Mendesak', 'Pembelian Kendaraan'];

        // --- Membuat Pinjaman dengan status MENUNGGU ---
        $this->command->info('Membuat data pinjaman status MENUNGGU...');
        for ($i = 0; $i < 10; $i++) {
            Pinjaman::create([
                'user_id'           => $anggotaIds->random(),
                'jumlah_pinjaman'   => rand(10, 100) * 100000,
                'margin'            => rand(1, 10),
                'tenor'             => collect([6, 12, 18, 24])->random(),
                'keterangan'        => collect($keteranganList)->random(),
                'tanggal_pengajuan' => now()->subDays(rand(1, 30)),
                'status'            => 'Menunggu Persetujuan',
            ]);
        }
        
        // --- Membuat Pinjaman dengan status DITOLAK ---
        $this->command->info('Membuat data pinjaman status DITOLAK...');
        for ($i = 0; $i < 10; $i++) {
            Pinjaman::create([
                'user_id'           => $anggotaIds->random(),
                'jumlah_pinjaman'   => rand(10, 100) * 100000,
                'margin'            => rand(1, 10),
                'tenor'             => collect([6, 12, 18, 24])->random(),
                'keterangan'        => collect($keteranganList)->random(),
                'tanggal_pengajuan' => now()->subDays(rand(31, 90)),
                'status'            => 'Ditolak',
            ]);
        }

        // --- Membuat Pinjaman dengan status DISETUJUI (AKTIF) ---
        $this->command->info('Membuat data pinjaman status DISETUJUI...');
        for ($i = 0; $i < 10; $i++) {
            $pokok = rand(10, 100) * 100000;
            $margin = rand(1, 10);
            $tenor = collect([6, 12, 18, 24])->random();

            $pinjaman = Pinjaman::create([
                'user_id'           => $anggotaIds->random(),
                'jumlah_pinjaman'   => $pokok,
                'margin'            => $margin,
                'tenor'             => $tenor,
                'keterangan'        => collect($keteranganList)->random(),
                'tanggal_pengajuan' => now()->subMonths(rand(2, 6)),
                'tanggal_disetujui' => now()->subMonths(rand(1, 5)),
                'status'            => 'Disetujui',
            ]);
            
            // Buat beberapa angsuran acak
            $totalMargin = $pokok * ($margin / 100);
            $totalTagihan = $pokok + $totalMargin;
            $angsuranPerBulan = $totalTagihan / $tenor;
            $jumlahAngsuranDibuat = rand(1, $tenor - 1);
            
            for ($j = 1; $j <= $jumlahAngsuranDibuat; $j++) {
                Angsuran::create([
                    'pinjaman_id'   => $pinjaman->id,
                    'jumlah_bayar'  => round($angsuranPerBulan),
                    'tanggal_bayar' => Carbon::parse($pinjaman->tanggal_disetujui)->addMonths($j),
                    'angsuran_ke'   => $j,
                    'processed_by'  => $superAdmin->id, // PERBAIKAN DI SINI
                ]);
            }
        }

        // --- Membuat Pinjaman dengan status LUNAS ---
        $this->command->info('Membuat data pinjaman status LUNAS...');
        for ($i = 0; $i < 10; $i++) {
            $pokok = rand(10, 100) * 100000;
            $margin = rand(1, 10);
            $tenor = collect([6, 12])->random();
            
            $pinjaman = Pinjaman::create([
                'user_id'           => $anggotaIds->random(),
                'jumlah_pinjaman'   => $pokok,
                'margin'            => $margin,
                'tenor'             => $tenor,
                'keterangan'        => collect($keteranganList)->random(),
                'tanggal_pengajuan' => now()->subMonths(rand(12, 24)),
                'tanggal_disetujui' => now()->subMonths(rand(10, 11)),
                'status'            => 'Lunas',
            ]);

            // Buat angsuran lengkap
            $totalMargin = $pokok * ($margin / 100);
            $totalTagihan = $pokok + $totalMargin;
            $angsuranPerBulan = $totalTagihan / $tenor;
            
            for ($j = 1; $j <= $tenor; $j++) {
                Angsuran::create([
                    'pinjaman_id'   => $pinjaman->id,
                    'jumlah_bayar'  => round($angsuranPerBulan),
                    'tanggal_bayar' => Carbon::parse($pinjaman->tanggal_disetujui)->addMonths($j),
                    'angsuran_ke'   => $j,
                    'processed_by'  => $superAdmin->id, // PERBAIKAN DI SINI
                ]);
            }
        }

        $this->command->info('Selesai membuat data pinjaman acak.');
    }
}

