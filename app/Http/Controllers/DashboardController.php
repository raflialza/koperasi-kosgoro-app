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

        if ($user->role === 'admin' || $user->role === 'super_admin') {
            // --- PERBAIKAN LOGIKA DASHBOARD ADMIN ---
            $jumlahAnggota = User::where('role', 'anggota')->count();
            $totalSimpanan = Simpanan::sum('jumlah');
            
            // Menggunakan status 'Disetujui' yang baru
            $totalPinjamanAktif = Pinjaman::where('status', 'Disetujui')->sum('jumlah_pinjaman');
            
            // Menggunakan status 'Menunggu Persetujuan' yang baru
            $pengajuanMenunggu = Pinjaman::where('status', 'Menunggu Persetujuan')->count();

            return view('admin.home', compact('jumlahAnggota', 'totalSimpanan', 'totalPinjamanAktif', 'pengajuanMenunggu'));
        
        } elseif ($user->role === 'anggota') {
            $totalSimpanan = Simpanan::where('user_id', $user->id)->sum('jumlah');
            
            $pinjamanAktif = Pinjaman::where('user_id', $user->id)
                                     ->where('status', 'Disetujui')
                                     ->first();

            $sisaPinjaman = 0;
            if ($pinjamanAktif) {
                $marginAmount = $pinjamanAktif->jumlah_pinjaman * ($pinjamanAktif->margin / 100);
                $totalTagihan = $pinjamanAktif->jumlah_pinjaman + $marginAmount;
                $totalTerbayar = $pinjamanAktif->angsuran()->sum('jumlah_bayar');
                $sisaPinjaman = $totalTagihan - $totalTerbayar;
                $sisaPinjaman = max(0, $sisaPinjaman); // Pastikan tidak negatif
            }
            
            return view('dashboard', compact('totalSimpanan', 'pinjamanAktif', 'sisaPinjaman'));
        }

        // Fallback jika role tidak terdefinisi
        return redirect()->route('login');
    }
}
