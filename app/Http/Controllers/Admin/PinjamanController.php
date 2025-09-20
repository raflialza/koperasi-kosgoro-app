<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Simpanan; // Pastikan Simpanan di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PinjamanController extends Controller
{
    /**
     * Menampilkan daftar semua pinjaman dengan filter status dan pencarian.
     * Metode ini sekarang menangani semua daftar pinjaman (menunggu, disetujui, ditolak, lunas).
     */
    public function index(Request $request)
    {
        // --- PERBAIKAN DI SINI ---
        // Mengubah status default menjadi 'Disetujui' untuk halaman "Semua Pinjaman"
        $status = $request->query('status', 'Disetujui'); 
        $search = $request->query('search', '');

        $query = Pinjaman::with('user');

        // Terapkan filter berdasarkan status dari tab yang aktif
        if ($status) {
            $query->where('status', $status);
        }

        // Terapkan filter pencarian
        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }

        $pinjaman = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        // Jika ini adalah permintaan dari JavaScript (AJAX), kirimkan hanya bagian tabelnya
        if ($request->ajax()) {
            if ($status == 'Menunggu Persetujuan') {
                 return view('admin.pinjaman.partials.list-pengajuan', compact('pinjaman'))->render();
            }
            return view('admin.pinjaman.partials.list-semua-pinjaman', ['semuaPinjaman' => $pinjaman, 'status' => $status])->render();
        }

        // Jika tidak, kirimkan halaman lengkap
        if($status == 'Menunggu Persetujuan'){
            return view('admin.pinjaman.daftar-pengajuan', compact('pinjaman', 'search'));
        }
        
        return view('admin.pinjaman.index', ['semuaPinjaman' => $pinjaman, 'status' => $status, 'search' => $search]);
    }

    /**
     * Menampilkan detail spesifik dari satu pinjaman.
     */
    public function show($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])->findOrFail($id);
        
        // --- PERHITUNGAN ULANG BERDASARKAN MARGIN (DISESUAIKAN) ---
        $pokok = $pinjaman->jumlah_pinjaman;
        $totalMarginAmount = $pokok * ($pinjaman->margin / 100);
        $totalTagihan = $pokok + $totalMarginAmount;
        $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;
        
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaTagihan = $totalTagihan - $totalTerbayar;
        $persentaseTerbayar = $totalTagihan > 0 ? ($totalTerbayar / $totalTagihan) * 100 : 0;
        
        // Mengatasi sisa pinjaman negatif karena pembulatan
        $sisaTagihan = max(0, $sisaTagihan);

        return view('admin.pinjaman.show', compact(
            'pinjaman', 
            'totalTagihan', 
            'angsuranPerBulan', 
            'sisaTagihan',
            'persentaseTerbayar'
        ));
    }

    /**
     * Memperbarui status pengajuan pinjaman (menjadi 'Disetujui' atau 'Ditolak').
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Disetujui,Ditolak']);
        
        $pinjaman = Pinjaman::findOrFail($id);
        
        if ($pinjaman->status === 'Menunggu Persetujuan') {
            $pinjaman->status = $request->status;
            $pinjaman->approved_by = Auth::id();

            if ($request->status == 'Disetujui') {
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
            'pinjaman_id'  => $pinjaman->id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_bayar'=> $request->tanggal_bayar,
            'angsuran_ke'  => $pinjaman->angsuran->count() + 1,
            'processed_by' => Auth::id(),
        ]);
        
        // --- PERHITUNGAN ULANG UNTUK CEK LUNAS ---
        $totalTerbayar = $pinjaman->fresh()->angsuran->sum('jumlah_bayar');
        $totalTagihan = $pinjaman->jumlah_pinjaman + ($pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100));

        // Toleransi 1 rupiah untuk pembulatan
        if ($totalTerbayar >= ($totalTagihan - 1)) {
            $pinjaman->status = 'Lunas';
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
        $request->validate([
            'pinjaman_ids' => 'required|array|min:1',
            'pinjaman_ids.*' => 'exists:pinjaman,id',
        ]);

        $berhasil = 0;
        foreach ($request->pinjaman_ids as $pinjamanId) {
            $pinjaman = Pinjaman::with('angsuran')->find($pinjamanId);

            if ($pinjaman && $pinjaman->status == 'Disetujui') {
                // --- PERHITUNGAN ULANG UNTUK ANGSURAN MASSAL ---
                $totalTagihan = $pinjaman->jumlah_pinjaman + ($pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100));
                $angsuranPerBulan = round($totalTagihan / $pinjaman->tenor);
                
                Angsuran::create([
                    'pinjaman_id'  => $pinjaman->id,
                    'jumlah_bayar' => $angsuranPerBulan,
                    'tanggal_bayar'=> now(),
                    'angsuran_ke'  => $pinjaman->angsuran->count() + 1,
                    'processed_by' => Auth::id(),
                ]);

                // Cek ulang status lunas setelah pembayaran
                if ($pinjaman->fresh()->angsuran->sum('jumlah_bayar') >= ($totalTagihan - 1)) {
                    $pinjaman->status = 'Lunas';
                    $pinjaman->save();
                }
                $berhasil++;
            }
        }

        return redirect()->route('admin.pinjaman.index', ['status' => 'Disetujui'])
                         ->with('success', "$berhasil angsuran berhasil dibayar secara massal.");
    }

    /**
     * Mencetak invoice pinjaman dalam format PDF.
     */
    public function cetakInvoice($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])->findOrFail($id);
        
        // --- PERHITUNGAN ULANG UNTUK INVOICE ---
        $totalTagihan = $pinjaman->jumlah_pinjaman + ($pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100));
        $sisaPinjaman = $totalTagihan - $pinjaman->angsuran->sum('jumlah_bayar');

        $daftarSimpanan = Simpanan::where('user_id', $pinjaman->user_id)->get();
        
        $pdf = Pdf::loadView('admin.pinjaman.invoice-pdf', compact('pinjaman', 'sisaPinjaman', 'daftarSimpanan'));
        $fileName = 'INV-ADMIN-' . $pinjaman->user->id_anggota . '-' . $pinjaman->id . '.pdf';
        
        return $pdf->stream($fileName);
    }
}

