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
        // 1. Ambil semua data simpanan
        $simpanan = Simpanan::with('user')->get()->map(function ($item) {
            return (object) [
                'tanggal' => $item->tanggal_transaksi,
                'nama_anggota' => $item->user->nama,
                'jenis' => 'Simpanan ' . $item->jenis_simpanan,
                'jumlah' => $item->jumlah,
            ];
        });

        // 2. Ambil semua data angsuran
        $angsuran = Angsuran::with('pinjaman.user')->get()->map(function ($item) {
            return (object) [
                'tanggal' => $item->tanggal_bayar,
                'nama_anggota' => $item->pinjaman->user->nama,
                'jenis' => 'Angsuran Pinjaman',
                'jumlah' => $item->jumlah_bayar,
            ];
        });
        
        // 3. Ambil semua data pencairan pinjaman (pinjaman yang sudah disetujui)
        $pinjaman = Pinjaman::with('user')->whereNotNull('tanggal_disetujui')->get()->map(function ($item) {
            return (object) [
                'tanggal' => $item->tanggal_disetujui,
                'nama_anggota' => $item->user->nama,
                'jenis' => 'Pencairan Pinjaman',
                'jumlah' => $item->jumlah_pinjaman,
            ];
        });

        // 4. Gabungkan semua jenis transaksi
        $semuaTransaksi = $simpanan->concat($angsuran)->concat($pinjaman);

        // 5. Urutkan berdasarkan tanggal dari yang terbaru
        $semuaTransaksi = $semuaTransaksi->sortByDesc('tanggal');

        return view('admin.transaksi.index', compact('semuaTransaksi'));
    }
}
