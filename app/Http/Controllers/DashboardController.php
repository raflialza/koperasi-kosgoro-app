<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- JIKA PENGGUNA ADALAH ADMIN ATAU SUPER ADMIN ---
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            $jumlahAnggota = User::where('role', 'anggota')->count();
            $totalSimpanan = Simpanan::sum('jumlah');
            $totalPinjamanAktif = Pinjaman::where('status', 'Disetujui')->sum('jumlah_pinjaman');
            $pengajuanMenunggu = Pinjaman::where('status', 'Menunggu Persetujuan')->count();

            // Selalu memuat view 'home' dengan data untuk admin
            return view('home', compact('jumlahAnggota', 'totalSimpanan', 'totalPinjamanAktif', 'pengajuanMenunggu'));
        }

        // --- JIKA PENGGUNA ADALAH ANGGOTA ---
        $totalSimpanan = Simpanan::where('user_id', $user->id)->sum('jumlah');
        $pinjamanAktif = Pinjaman::with('angsuran')
                                ->where('user_id', $user->id)
                                ->where('status', 'Disetujui')
                                ->first();

        $sisaTagihan = 0;
        if ($pinjamanAktif) {
            $totalMargin = $pinjamanAktif->jumlah_pinjaman * ($pinjamanAktif->margin / 100);
            $totalTagihan = $pinjamanAktif->jumlah_pinjaman + $totalMargin;
            $totalTerbayar = $pinjamanAktif->angsuran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalTerbayar;
            $sisaTagihan = max(0, $sisaTagihan);
        }

        // Selalu memuat view 'home' dengan data untuk anggota
        return view('home', compact('totalSimpanan', 'pinjamanAktif', 'sisaTagihan'));
    }
}

