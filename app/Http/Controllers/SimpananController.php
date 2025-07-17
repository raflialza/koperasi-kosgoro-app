<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    // Untuk anggota
    public function simpananSaya()
    {
        $user = Auth::user();

        $simpanan = Simpanan::where('user_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan')
            ->get();

        return view('anggota.simpanan-saya', compact('simpanan'));
    }

    // Untuk admin (misalnya melihat semua simpanan)
    public function index()
    {
        $simpanan = Simpanan::with('user')->latest()->get();
        return view('admin.kelola-simpanan', compact('simpanan'));
    }
}
