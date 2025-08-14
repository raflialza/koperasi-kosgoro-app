<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        // --- PERBAIKAN DI SINI ---
        // Menghitung kembali data yang diperlukan oleh view
        $totalTagihan = $pinjaman->total_tagihan;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;

        // Mengirim semua data yang dibutuhkan ke view
        return view('anggota.detail-pinjaman', compact('pinjaman', 'sisaPinjaman', 'angsuranPerBulan'));
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
                         ->with('success', 'Pengajuan berhasil! Mohon tunggu konfirmasi dari admin.');
    }

    public function cetakInvoicePinjaman($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])
                            ->where('user_id', Auth::id())
                            ->findOrFail($id);
        
        $sisaPinjaman = $pinjaman->total_tagihan - $pinjaman->angsuran->sum('jumlah_bayar');

        $pdf = Pdf::loadView('anggota.invoice-pinjaman-pdf', compact('pinjaman', 'sisaPinjaman'));
        
        // Membuat nama file yang unik, contoh: INV-AGT001-123.pdf
        $fileName = 'INV-' . $pinjaman->user->id_anggota . '-' . $pinjaman->id . '.pdf';

        return $pdf->stream($fileName);
    }
}
