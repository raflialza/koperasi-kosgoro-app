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
     * Menampilkan halaman untuk memilih filter laporan.
     */
    public function index()
    {
        return view('admin.laporan.index');
    }

    /**
     * Mencetak laporan PDF untuk simpanan dengan filter tanggal.
     */
    public function simpananPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $semuaSimpanan = Simpanan::with('user')
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
            
        $data = [
            'semuaSimpanan' => $semuaSimpanan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPokok' => $semuaSimpanan->where('jenis_simpanan', 'Pokok')->sum('jumlah'),
            'totalWajib' => $semuaSimpanan->where('jenis_simpanan', 'Wajib')->sum('jumlah'),
            'totalSukarela' => $semuaSimpanan->where('jenis_simpanan', 'Sukarela')->sum('jumlah'),
            'totalSemua' => $semuaSimpanan->sum('jumlah')
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-simpanan', $data);
        return $pdf->stream('laporan-simpanan-'.$startDate.'-'.$endDate.'.pdf');
    }

    /**
     * FUNGSI BARU: Mencetak laporan PDF untuk semua simpanan tanpa filter tanggal.
     */
    public function simpananPdfKeseluruhan()
    {
        $semuaSimpanan = Simpanan::with('user')->orderBy('tanggal_transaksi', 'desc')->get();
        $data = [
            'semuaSimpanan' => $semuaSimpanan,
            'startDate' => null, // Menandakan ini laporan keseluruhan
            'endDate' => null,
            'totalPokok' => $semuaSimpanan->where('jenis_simpanan', 'Pokok')->sum('jumlah'),
            'totalWajib' => $semuaSimpanan->where('jenis_simpanan', 'Wajib')->sum('jumlah'),
            'totalSukarela' => $semuaSimpanan->where('jenis_simpanan', 'Sukarela')->sum('jumlah'),
            'totalSemua' => $semuaSimpanan->sum('jumlah')
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-simpanan', $data);
        return $pdf->stream('laporan-keseluruhan-simpanan.pdf');
    }

    /**
     * Mencetak laporan PDF untuk pinjaman dengan filter tanggal.
     */
    public function pinjamanPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $semuaPinjaman = Pinjaman::with('user')
            ->whereBetween('tanggal_pengajuan', [$startDate, $endDate])
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();
            
        // --- PERBAIKAN PERHITUNGAN DI SINI ---
        $pinjamanAktif = $semuaPinjaman->where('status', '!=', 'Ditolak');
        $totalPokok = $pinjamanAktif->sum('jumlah_pinjaman');
        $totalMargin = $pinjamanAktif->sum(function($pinjaman) {
            return $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        });
        $totalTagihan = $totalPokok + $totalMargin;

        $data = [
            'semuaPinjaman' => $semuaPinjaman,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPokok' => $totalPokok,
            'totalMargin' => $totalMargin,
            'totalTagihan' => $totalTagihan,
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-pinjaman', $data);
        return $pdf->stream('laporan-pinjaman-'.$startDate.'-'.$endDate.'.pdf');
    }

    /**
     * Mencetak laporan PDF untuk semua pinjaman tanpa filter tanggal.
     */
    public function pinjamanPdfKeseluruhan()
    {
        $semuaPinjaman = Pinjaman::with('user')->orderBy('tanggal_pengajuan', 'desc')->get();
        
        // --- PERBAIKAN PERHITUNGAN DI SINI ---
        $pinjamanAktif = $semuaPinjaman->where('status', '!=', 'Ditolak');
        $totalPokok = $pinjamanAktif->sum('jumlah_pinjaman');
        $totalMargin = $pinjamanAktif->sum(function($pinjaman) {
            return $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        });
        $totalTagihan = $totalPokok + $totalMargin;

        $data = [
            'semuaPinjaman' => $semuaPinjaman,
            'startDate' => null,
            'endDate' => null,
            'totalPokok' => $totalPokok,
            'totalMargin' => $totalMargin,
            'totalTagihan' => $totalTagihan,
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-pinjaman', $data);
        return $pdf->stream('laporan-keseluruhan-pinjaman.pdf');
    }
}
