<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Mencetak laporan PDF untuk semua transaksi simpanan.
     */
    public function simpananPdf()
    {
        $semuaSimpanan = Simpanan::with('user')->orderBy('tanggal_transaksi', 'desc')->get();
        $data = [
            'semuaSimpanan' => $semuaSimpanan,
            'totalPokok'    => $semuaSimpanan->where('jenis_simpanan', 'Pokok')->sum('jumlah'),
            'totalWajib'    => $semuaSimpanan->where('jenis_simpanan', 'Wajib')->sum('jumlah'),
            'totalSukarela' => $semuaSimpanan->where('jenis_simpanan', 'Sukarela')->sum('jumlah'),
            'totalSemua'    => $semuaSimpanan->sum('jumlah')
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-simpanan', $data);
        return $pdf->stream('laporan-semua-simpanan.pdf');
    }

    /**
     * Mencetak laporan PDF untuk semua transaksi pinjaman.
     */
    public function pinjamanPdf()
    {
        $semuaPinjaman = Pinjaman::with('user')->orderBy('tanggal_pengajuan', 'desc')->get();
        $data = [
            'semuaPinjaman'     => $semuaPinjaman,
            'totalPinjaman'     => $semuaPinjaman->where('status', '!=', 'ditolak')->sum('total_tagihan'),
            'pinjamanDisetujui' => $semuaPinjaman->where('status', 'disetujui')->count(),
            'pinjamanLunas'     => $semuaPinjaman->where('status', 'lunas')->count(),
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-pinjaman', $data);
        return $pdf->stream('laporan-semua-pinjaman.pdf');
    }
}