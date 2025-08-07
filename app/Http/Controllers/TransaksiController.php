<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Fungsi Khusus untuk Role ANGGOTA
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman riwayat simpanan milik anggota yang login.
     */
    public function lihatSimpanan()
    {
        $daftarSimpanan = Simpanan::where('user_id', Auth::id())->latest()->get();
        return view('anggota.simpanan', compact('daftarSimpanan'));
    }

    /**
     * Menampilkan halaman riwayat pinjaman anggota.
     */
    public function lihatPinjaman()
    {
        $daftarPinjaman = Pinjaman::where('user_id', Auth::id())->latest()->get();
        return view('anggota.pinjaman', compact('daftarPinjaman'));
    }

    /**
     * Menampilkan detail spesifik dari satu pinjaman milik anggota.
     */
    public function detailPinjamanAnggota($id)
    {
        $pinjaman = Pinjaman::with('angsuran')
                            ->where('user_id', Auth::id()) // Keamanan
                            ->findOrFail($id);
        
        $sisaPinjaman = $pinjaman->total_tagihan - $pinjaman->angsuran->sum('jumlah_bayar');

        return view('anggota.detail-pinjaman', compact('pinjaman', 'sisaPinjaman'));
    }

    /**
     * Menampilkan form untuk mengajukan pinjaman baru.
     */
    public function ajukanPinjamanForm()
    {
        return view('anggota.ajukan-pinjaman');
    }

    /**
     * Memproses dan menyimpan pengajuan pinjaman baru dari anggota.
     */
    public function prosesAjukanPinjaman(Request $request)
    {
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'tenor'           => 'required|integer|min:1',
            'keperluan'       => 'required|string|max:255',
        ]);

        $pokok = $request->jumlah_pinjaman;
        $bunga = 0.10; // Bunga 10%
        $totalTagihan = $pokok + ($pokok * $bunga);

        Pinjaman::create([
            'user_id'           => Auth::id(),
            'jumlah_pinjaman'   => $pokok,
            'persentase_bunga'  => $bunga,
            'total_tagihan'     => $totalTagihan,
            'tenor'             => $request->tenor,
            'keperluan'         => $request->keperluan,
            'tanggal_pengajuan' => now(),
            'status'            => 'menunggu',
        ]);

        return redirect()->route('anggota.pinjaman.riwayat')
                         ->with('success', 'Pengajuan pinjaman Anda berhasil dikirim!');
    }
}