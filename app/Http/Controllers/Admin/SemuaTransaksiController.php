<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Angsuran;
use App\Models\Pinjaman; // Ditambahkan
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SemuaTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenis = $request->input('jenis');
        $search = $request->input('search');

        // --- 1. Ambil Data Simpanan (Pemasukan) ---
        $querySimpanan = Simpanan::with('user')->select('id', 'user_id', 'jenis_simpanan as jenis', 'jumlah', 'keterangan', 'created_at');
        if ($startDate && $endDate) {
            $querySimpanan->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }
        if ($search) {
            $querySimpanan->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }
        $simpanan = $querySimpanan->get()->map(function ($item) {
            return (object) [
                'tanggal' => $item->created_at,
                'anggota' => $item->user->nama ?? 'N/A',
                'id_anggota' => $item->user->id_anggota ?? 'N/A',
                'jenis_transaksi' => 'Simpanan',
                'keterangan' => 'Simpanan ' . $item->jenis,
                'pemasukan' => $item->jumlah,
                'pengeluaran' => 0,
            ];
        });

        // --- 2. Ambil Data Angsuran (Pemasukan) ---
        $queryAngsuran = Angsuran::with('pinjaman.user')->select('id', 'pinjaman_id', 'jumlah_bayar', 'angsuran_ke', 'created_at');
        if ($startDate && $endDate) {
            $queryAngsuran->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }
        if ($search) {
            $queryAngsuran->whereHas('pinjaman.user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }
        $angsuran = $queryAngsuran->get()->map(function ($item) {
            return (object) [
                'tanggal' => $item->created_at,
                'anggota' => $item->pinjaman->user->nama ?? 'N/A',
                'id_anggota' => $item->pinjaman->user->id_anggota ?? 'N/A',
                'jenis_transaksi' => 'Angsuran',
                'keterangan' => 'Pembayaran Angsuran ke-' . $item->angsuran_ke,
                'pemasukan' => $item->jumlah_bayar,
                'pengeluaran' => 0,
            ];
        });

        // --- 3. Ambil Data Pinjaman (Pengeluaran) ---
        $queryPinjaman = Pinjaman::with('user')->where('status', 'Disetujui')->select('id', 'user_id', 'jumlah_pinjaman', 'tanggal_disetujui');
        if ($startDate && $endDate) {
            $queryPinjaman->whereBetween('tanggal_disetujui', [$startDate, $endDate]);
        }
        if ($search) {
            $queryPinjaman->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }
        $pinjaman = $queryPinjaman->get()->map(function ($item) {
            return (object) [
                'tanggal' => $item->tanggal_disetujui,
                'anggota' => $item->user->nama ?? 'N/A',
                'id_anggota' => $item->user->id_anggota ?? 'N/A',
                'jenis_transaksi' => 'Pinjaman',
                'keterangan' => 'Pencairan Pinjaman',
                'pemasukan' => 0,
                'pengeluaran' => $item->jumlah_pinjaman,
            ];
        });

        // --- 4. Gabungkan, Saring, Urutkan ---
        $semuaTransaksi = new Collection(array_merge($simpanan->all(), $angsuran->all(), $pinjaman->all()));

        if ($jenis) {
            $semuaTransaksi = $semuaTransaksi->filter(function ($item) use ($jenis) {
                return strtolower($item->jenis_transaksi) === $jenis;
            });
        }
        
        $transaksiTerurut = $semuaTransaksi->sortByDesc('tanggal');

        // --- 5. Terapkan Paginasi Manual ---
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $transaksiTerurut->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $transaksi = new LengthAwarePaginator($currentPageItems, $transaksiTerurut->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query(),
        ]);

        return view('admin.transaksi.index', compact('transaksi', 'startDate', 'endDate', 'jenis', 'search'));
    }
}

