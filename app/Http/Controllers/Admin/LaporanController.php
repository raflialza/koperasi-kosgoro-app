<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SimpananExport;
use App\Exports\PinjamanExport;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function simpananPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Simpanan::with('user');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }
        $semuaSimpanan = $query->get();
        
        $totalPokok = $semuaSimpanan->where('jenis_simpanan', 'Pokok')->sum('jumlah');
        $totalWajib = $semuaSimpanan->where('jenis_simpanan', 'Wajib')->sum('jumlah');
        $totalSukarela = $semuaSimpanan->where('jenis_simpanan', 'Sukarela')->sum('jumlah');
        $totalSemua = $semuaSimpanan->sum('jumlah');

        $pdf = Pdf::loadView('admin.laporan.pdf-simpanan', compact(
            'semuaSimpanan',
            'startDate',
            'endDate',
            'totalPokok',
            'totalWajib',
            'totalSukarela',
            'totalSemua'
        ));
        return $pdf->stream('laporan-simpanan.pdf');
    }

    public function simpananExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Simpanan::with('user');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }
        $data = $query->get();
        
        $fileName = 'laporan-simpanan-' . now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new SimpananExport($data), $fileName);
    }

    public function pinjamanPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = Pinjaman::with('user');
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pengajuan', [$startDate, $endDate]);
        }
        $semuaPinjaman = $query->get();
        
        $totalPokok = $semuaPinjaman->sum('jumlah_pinjaman');
        $totalMargin = $semuaPinjaman->reduce(function ($carry, $item) {
            return $carry + ($item->jumlah_pinjaman * ($item->margin / 100));
        }, 0);
        $totalTagihan = $totalPokok + $totalMargin;

        $pdf = Pdf::loadView('admin.laporan.pdf-pinjaman', compact('semuaPinjaman', 'totalPokok', 'totalMargin', 'totalTagihan', 'startDate', 'endDate'));
        return $pdf->stream('laporan-pinjaman.pdf');
    }

    public function pinjamanExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pinjaman::with('user');
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_pengajuan', [$startDate, $endDate]);
        }
        $data = $query->get();
        
        $fileName = 'laporan-pinjaman-' . now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new PinjamanExport($data), $fileName);
    }

    /**
     * --- FUNGSI BARU UNTUK SHU ---
     * Menampilkan halaman perhitungan SHU.
     */
    public function shu(Request $request)
    {
        // Default ke tahun saat ini jika tidak ada input
        $tahun = $request->input('tahun', date('Y'));

        // 1. Total Simpanan (Pokok + Wajib) pada akhir tahun yang dipilih
        $totalSimpanan = Simpanan::whereIn('jenis_simpanan', ['Pokok', 'Wajib'])
                                 ->whereYear('created_at', '<=', $tahun)
                                 ->sum('jumlah');

        // 2. Total Pendapatan dari Margin Pinjaman pada tahun yang dipilih
        $pinjamanLunasTahunIni = Pinjaman::where('status', 'Lunas')
                                         ->whereYear('tanggal_disetujui', $tahun)
                                         ->get();
        
        $totalPendapatanMargin = $pinjamanLunasTahunIni->reduce(function ($carry, $item) {
            return $carry + ($item->jumlah_pinjaman * ($item->margin / 100));
        }, 0);
        
        // 3. SHU (Sisa Hasil Usaha)
        // Misal: SHU adalah 40% dari total pendapatan margin
        $persentaseShu = 0.40; 
        $shu = $totalPendapatanMargin * $persentaseShu;

        return view('admin.shu.index', compact('tahun', 'totalSimpanan', 'totalPendapatanMargin', 'shu'));
    }
    
    /**
     * --- FUNGSI BARU UNTUK SHU ---
     * Mencetak laporan SHU dalam format PDF.
     */
    public function shuPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Lakukan perhitungan yang sama seperti di method shu()
        $totalSimpanan = Simpanan::whereIn('jenis_simpanan', ['Pokok', 'Wajib'])
                                 ->whereYear('created_at', '<=', $tahun)
                                 ->sum('jumlah');

        $pinjamanLunasTahunIni = Pinjaman::where('status', 'Lunas')
                                         ->whereYear('tanggal_disetujui', $tahun)
                                         ->get();
        
        $totalPendapatanMargin = $pinjamanLunasTahunIni->reduce(function ($carry, $item) {
            return $carry + ($item->jumlah_pinjaman * ($item->margin / 100));
        }, 0);
        
        $persentaseShu = 0.40; 
        $shu = $totalPendapatanMargin * $persentaseShu;

        // Contoh alokasi SHU (bisa disimpan di database/config)
        $alokasi = [
            'jasa_modal' => 0.25, // 25%
            'jasa_usaha' => 0.40, // 40%
            'dana_cadangan' => 0.10, // 10%
            'dana_pengurus' => 0.10, // 10%
            'dana_sosial' => 0.15, // 15%
        ];

        $shuJasaModal = $shu * $alokasi['jasa_modal'];
        $shuJasaUsaha = $shu * $alokasi['jasa_usaha'];
        $shuDanaCadangan = $shu * $alokasi['dana_cadangan'];
        $shuDanaPengurus = $shu * $alokasi['dana_pengurus'];
        $shuDanaSosial = $shu * $alokasi['dana_sosial'];

        $pdf = Pdf::loadView('admin.laporan.pdf-shu', compact(
            'tahun',
            'totalSimpanan',
            'totalPendapatanMargin',
            'shu',
            'alokasi',
            'shuJasaModal',
            'shuJasaUsaha',
            'shuDanaCadangan',
            'shuDanaPengurus',
            'shuDanaSosial'
        ));

        return $pdf->stream('laporan-shu-'.$tahun.'.pdf');
    }
}

