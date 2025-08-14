<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ShuController extends Controller
{
    /**
     * Menampilkan halaman form untuk input data SHU.
     */
    public function index()
    {
        return view('admin.shu.index');
    }

    /**
     * Menghitung dan mencetak laporan SHU dalam format PDF.
     */
    public function cetakPdf(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric|min:2020',
            'total_shu' => 'required|numeric|min:0',
        ]);

        $tahun = $request->tahun;
        $totalShu = $request->total_shu;

        // Mengambil semua anggota beserta total simpanan mereka (pokok + wajib)
        $anggota = User::where('role', 'anggota')
            ->withSum(['simpanan' => function ($query) {
                $query->whereIn('jenis_simpanan', ['Pokok', 'Wajib']);
            }], 'jumlah')
            ->get();

        // Menghitung total simpanan dari semua anggota
        $totalSimpananKoperasi = $anggota->sum('simpanan_sum_jumlah');

        // Menghitung SHU per anggota
        $dataShu = $anggota->map(function ($user) use ($totalSimpananKoperasi, $totalShu) {
            $bagianShu = 0;
            if ($totalSimpananKoperasi > 0) {
                $persentaseSimpanan = ($user->simpanan_sum_jumlah / $totalSimpananKoperasi);
                $bagianShu = $persentaseSimpanan * $totalShu;
            }
            return [
                'id_anggota' => $user->id_anggota,
                'nama' => $user->nama,
                'total_simpanan' => $user->simpanan_sum_jumlah,
                'bagian_shu' => $bagianShu,
            ];
        });

        $data = [
            'tahun' => $tahun,
            'totalShu' => $totalShu,
            'dataShu' => $dataShu,
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf-shu', $data);
        return $pdf->stream('laporan-shu-'.$tahun.'.pdf');
    }
}
