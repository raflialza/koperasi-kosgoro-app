<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung jumlah anggota
        $jumlahAnggota = User::where('role', 'anggota')->count();

        // 2. Menghitung total semua simpanan
        $totalSimpanan = Simpanan::sum('jumlah');

        // 3. Menghitung total pinjaman yang masih aktif (belum lunas)
        $totalPinjamanAktif = Pinjaman::whereIn('status', ['disetujui', 'berjalan'])->sum('jumlah_pinjaman');

        // 4. Menghitung jumlah pengajuan pinjaman yang masih menunggu
        $pengajuanMenunggu = Pinjaman::where('status', 'menunggu')->count();

        // Kirim semua data ke view
        return view('admin.home', compact(
            'jumlahAnggota',
            'totalSimpanan',
            'totalPinjamanAktif',
            'pengajuanMenunggu'
        ));
    }
}