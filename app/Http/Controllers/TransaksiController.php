<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // Menampilkan riwayat simpanan anggota
    public function lihatSimpanan()
    {
        $daftarSimpanan = Simpanan::where('user_id', Auth::id())->latest()->get();
        return view('anggota.simpanan', compact('daftarSimpanan'));
    }

    // Menampilkan riwayat pinjaman anggota
    public function lihatPinjaman()
    {
        $daftarPinjaman = Pinjaman::where('user_id', Auth::id())->latest()->get();
        return view('anggota.pinjaman', compact('daftarPinjaman'));
    }

    // Menampilkan form pengajuan pinjaman
    public function ajukanPinjamanForm()
    {
        return view('anggota.ajukan-pinjaman');
    }

    // Memproses data dari form pengajuan pinjaman
    public function prosesAjukanPinjaman(Request $request)
    {
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'margin' => 'required|numeric|min:1|max:10',
            'tenor' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        Pinjaman::create([
            'user_id' => Auth::id(),
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'margin' => $request->margin,
            'tenor' => $request->tenor,
            'keterangan' => $request->keterangan,
            'tanggal_pengajuan' => now(),
            'status' => 'Menunggu Persetujuan',
        ]);

        return redirect()->route('anggota.pinjaman.riwayat')
                         ->with('success', 'Pengajuan pinjaman Anda berhasil dikirim dan akan segera diproses.');
    }
    
    // --- FUNGSI BARU UNTUK MELIHAT DETAIL PINJAMAN ---
    public function detailPinjamanAnggota($id)
    {
        // Mengambil data pinjaman spesifik milik user yang login untuk keamanan
        $pinjaman = Pinjaman::with('angsuran')
                            ->where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail(); // Akan error 404 jika pinjaman tidak ditemukan atau bukan miliknya

        // Lakukan perhitungan
        $marginAmount = $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        $totalTagihan = $pinjaman->jumlah_pinjaman + $marginAmount;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        $sisaPinjaman = max(0, $sisaPinjaman);
        $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;

        return view('anggota.detail-pinjaman', compact('pinjaman', 'totalTagihan', 'sisaPinjaman', 'angsuranPerBulan'));
    }


    // Mencetak invoice pinjaman untuk anggota
    public function cetakInvoicePinjaman($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Melakukan perhitungan yang diperlukan oleh view
        $totalMargin = $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        $totalTagihan = $pinjaman->jumlah_pinjaman + $totalMargin;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        $sisaPinjaman = max(0, $sisaPinjaman); // Pastikan tidak negatif

        // Mengirim semua variabel yang dibutuhkan ke view
        $pdf = Pdf::loadView('anggota.invoice-pinjaman-pdf', compact('pinjaman', 'totalMargin', 'totalTagihan', 'totalTerbayar', 'sisaPinjaman'));
        
        $fileName = 'INV-' . $pinjaman->user->id_anggota . '-' . $pinjaman->id . '.pdf';
        return $pdf->stream($fileName);
    }
}

