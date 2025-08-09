<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Http\Request;

class SemuaTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $jenisFilter = $request->query('jenis', 'semua'); // Default filter adalah 'semua'

        $simpanan = collect();
        $angsuran = collect();
        $pinjaman = collect();

        // Ambil data berdasarkan filter yang dipilih
        if ($jenisFilter == 'semua' || $jenisFilter == 'simpanan') {
            $simpanan = Simpanan::with('user')->get()->map(function ($item) {
                return (object) [
                    'tanggal' => $item->tanggal_transaksi,
                    'nama_anggota' => $item->user->nama,
                    'jenis' => 'Simpanan ' . $item->jenis_simpanan,
                    'jumlah' => $item->jumlah,
                    'tipe' => 'simpanan'
                ];
            });
        }

        if ($jenisFilter == 'semua' || $jenisFilter == 'angsuran') {
            $angsuran = Angsuran::with('pinjaman.user')->get()->map(function ($item) {
                return (object) [
                    'tanggal' => $item->tanggal_bayar,
                    'nama_anggota' => $item->pinjaman->user->nama,
                    'jenis' => 'Angsuran Pinjaman',
                    'jumlah' => $item->jumlah_bayar,
                    'tipe' => 'angsuran'
                ];
            });
        }

        if ($jenisFilter == 'semua' || $jenisFilter == 'pencairan') {
            $pinjaman = Pinjaman::with('user')->whereNotNull('tanggal_disetujui')->get()->map(function ($item) {
                return (object) [
                    'tanggal' => $item->tanggal_disetujui,
                    'nama_anggota' => $item->user->nama,
                    'jenis' => 'Pencairan Pinjaman',
                    'jumlah' => $item->jumlah_pinjaman,
                    'tipe' => 'pencairan'
                ];
            });
        }

        // Gabungkan semua koleksi data
        $semuaTransaksi = $simpanan->concat($angsuran)->concat($pinjaman);

        // Urutkan berdasarkan tanggal dari yang terbaru
        $semuaTransaksi = $semuaTransaksi->sortByDesc('tanggal');

        return view('admin.transaksi.index', [
            'semuaTransaksi' => $semuaTransaksi,
            'jenisFilter' => $jenisFilter
        ]);
    }
}
