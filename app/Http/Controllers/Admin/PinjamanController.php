<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamanController extends Controller
{
    /**
     * Menampilkan daftar semua pinjaman dengan filter dan pencarian.
     * Menggabungkan semua logika daftar pinjaman (aktif, lunas, menunggu, ditolak).
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'disetujui'); // Default tab adalah 'disetujui' (Aktif)
        $search = $request->query('search', '');

        // === PERBAIKAN LOGIKA QUERY ===
        // 1. Mulai dengan query dasar
        $query = Pinjaman::with('user');

        // 2. Terapkan filter berdasarkan status dari tab yang aktif
        $query->where('status', $status);
        // ==============================

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }

        $semuaPinjaman = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        if ($request->ajax()) {
            return view('admin.pinjaman.partials.list-semua-pinjaman', compact('semuaPinjaman'))->render();
        }

        return view('admin.pinjaman.index', compact('semuaPinjaman', 'status', 'search'));
    }

    public function pengajuan(Request $request)
    {
        $search = $request->query('search', '');
        $query = Pinjaman::with('user')->where('status', 'menunggu');

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }

        $daftarPengajuan = $query->latest('tanggal_pengajuan')->get();

        if ($request->ajax()) {
            // Jika ini permintaan dari JavaScript, kirim HANYA baris-baris tabelnya
            return view('admin.pinjaman.partials.list-pengajuan', compact('daftarPengajuan'))->render();
        }

        return view('admin.pinjaman.daftar-pengajuan', compact('daftarPengajuan', 'search'));
    }

    /**
     * Menampilkan detail spesifik dari satu pinjaman.
     * Halaman ini digunakan untuk melihat riwayat angsuran dan form pembayaran.
     */
    public function show($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])->findOrFail($id);
        
        $totalTagihan = $pinjaman->total_tagihan;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;
        $angsuranKe = $pinjaman->angsuran->count() + 1;

        $jumlahBayarDefault = round($angsuranPerBulan);
        if ($sisaPinjaman > 0 && $sisaPinjaman < $jumlahBayarDefault) {
            $jumlahBayarDefault = $sisaPinjaman;
        }

        return view('admin.pinjaman.show', compact('pinjaman', 'sisaPinjaman', 'angsuranPerBulan', 'angsuranKe', 'jumlahBayarDefault'));
    }

    /**
     * Memperbarui status pinjaman (menjadi 'disetujui' atau 'ditolak').
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);
        
        $pinjaman = Pinjaman::findOrFail($id);
        
        if ($pinjaman->status === 'menunggu') {
            $pinjaman->status = $request->status;
            $pinjaman->approved_by = Auth::id();

            if ($request->status == 'disetujui') {
                $pinjaman->tanggal_disetujui = now();
            }
            $pinjaman->save();
            
            return back()->with('success', 'Status pengajuan pinjaman berhasil diperbarui!');
        }
        
        return back()->with('error', 'Status pinjaman ini sudah diproses sebelumnya.');
    }

    /**
     * Menyimpan data pembayaran angsuran baru untuk satu pinjaman.
     */
    public function storeAngsuran(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
        ]);

        $pinjaman = Pinjaman::with('angsuran')->findOrFail($id);

        Angsuran::create([
            'pinjaman_id'   => $pinjaman->id,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'angsuran_ke'   => $pinjaman->angsuran->count() + 1,
            'processed_by'  => Auth::id(),
        ]);
        
        $totalTerbayar = $pinjaman->fresh()->angsuran->sum('jumlah_bayar');

        if ($totalTerbayar >= ($pinjaman->total_tagihan - 1)) {
            $pinjaman->status = 'lunas';
            $pinjaman->save();
        }

        return redirect()->route('admin.pinjaman.show', $pinjaman->id)
                         ->with('success', 'Pembayaran angsuran berhasil dicatat!');
    }

    /**
     * Memproses pembayaran angsuran untuk beberapa pinjaman sekaligus.
     */
    public function storeAngsuranMassal(Request $request)
    {
        $request->validate(['pinjaman_ids' => 'required|array|min:1']);

        $berhasil = 0;
        foreach ($request->pinjaman_ids as $pinjamanId) {
            $pinjaman = Pinjaman::with('angsuran')->find($pinjamanId);

            if ($pinjaman && $pinjaman->status == 'disetujui') {
                $totalTagihan = $pinjaman->total_tagihan;
                $angsuranPerBulan = $pinjaman->tenor > 0 ? round($totalTagihan / $pinjaman->tenor) : 0;
                
                Angsuran::create([
                    'pinjaman_id'  => $pinjaman->id,
                    'jumlah_bayar' => $angsuranPerBulan,
                    'tanggal_bayar'=> now(),
                    'angsuran_ke'  => $pinjaman->angsuran->count() + 1,
                    'processed_by' => Auth::id(),
                ]);

                // Cek status lunas
                if ($pinjaman->fresh()->angsuran->sum('jumlah_bayar') >= $totalTagihan) {
                    $pinjaman->status = 'lunas';
                    $pinjaman->save();
                }
                $berhasil++;
            }
        }

        return redirect()->route('admin.pinjaman.index', ['status' => 'aktif'])
                         ->with('success', "$berhasil angsuran berhasil dibayar secara massal.");
    }
}