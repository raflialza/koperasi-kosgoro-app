<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PinjamanController extends Controller
{
    /**
     * Menampilkan daftar semua pinjaman dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'Disetujui');
        $search = $request->query('search', '');

        $query = Pinjaman::with('user');

        if ($status === 'Menunggu Persetujuan') {
            $viewName = 'admin.pinjaman.daftar-pengajuan';
            $query->where('status', 'Menunggu Persetujuan');
        } else {
            $viewName = 'admin.pinjaman.index';
            if ($status) {
                 $query->where('status', $status);
            }
        }

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }

        $pinjamanData = $query->orderBy('tanggal_pengajuan', 'desc')->get();

        if ($request->ajax()) {
            $partialView = ($status === 'Menunggu Persetujuan') 
                           ? 'admin.pinjaman.partials.list-pengajuan' 
                           : 'admin.pinjaman.partials.list-semua-pinjaman';
            return view($partialView, ['semuaPinjaman' => $pinjamanData])->render();
        }
        
        // Menyesuaikan nama variabel yang dikirim ke view
        if ($status === 'Menunggu Persetujuan') {
             return view($viewName, ['daftarPengajuan' => $pinjamanData, 'search' => $search]);
        }
        return view($viewName, ['semuaPinjaman' => $pinjamanData, 'status' => $status, 'search' => $search]);
    }

    /**
     * Menampilkan detail spesifik dari satu pinjaman.
     */
    public function show($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])->findOrFail($id);
        
        $totalMargin = $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        $totalTagihan = $pinjaman->jumlah_pinjaman + $totalMargin;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        
        $sisaPinjaman = max(0, $sisaPinjaman);

        $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;
        $angsuranKe = $pinjaman->angsuran->count() + 1;
        $jumlahBayarDefault = round($angsuranPerBulan);
        
        if ($sisaPinjaman > 0 && $sisaPinjaman < $jumlahBayarDefault) {
            $jumlahBayarDefault = $sisaPinjaman;
        }

        return view('admin.pinjaman.show', compact('pinjaman', 'totalTagihan', 'sisaPinjaman', 'angsuranPerBulan', 'angsuranKe', 'jumlahBayarDefault'));
    }

    /**
     * Memperbarui status pinjaman (menjadi 'Disetujui' atau 'Ditolak').
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
        $totalMargin = $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        $totalTagihan = $pinjaman->jumlah_pinjaman + $totalMargin;

        Angsuran::create([
            'pinjaman_id'  => $pinjaman->id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'angsuran_ke'  => $pinjaman->angsuran->count() + 1,
            'processed_by' => Auth::id(),
        ]);
        
        $totalTerbayar = $pinjaman->fresh()->angsuran->sum('jumlah_bayar');

        if ($totalTerbayar >= ($totalTagihan - 1)) {
            $pinjaman->status = 'Lunas';
            $pinjaman->save();
        }

        return redirect()->route('admin.pinjaman.show', $pinjaman->id)
                         ->with('success', 'Pembayaran angsuran berhasil dicatat!');
    }
    
    /**
     * --- PERBAIKAN DI SINI ---
     * Mencetak invoice PDF untuk satu pinjaman.
     */
    public function cetakInvoice($id)
    {
        $pinjaman = Pinjaman::with(['user', 'angsuran'])->findOrFail($id);
        
        // Lakukan perhitungan yang diperlukan
        $totalMargin = $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        $totalTagihan = $pinjaman->jumlah_pinjaman + $totalMargin;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        $sisaPinjaman = max(0, $sisaPinjaman);

        // Muat view PDF yang baru dan kirim semua data yang dibutuhkan
        $pdf = Pdf::loadView('admin.pinjaman.invoice-pdf', compact(
            'pinjaman', 
            'totalMargin', 
            'totalTagihan', 
            'totalTerbayar', 
            'sisaPinjaman'
        ));
        
        $fileName = 'INV-' . $pinjaman->user->id_anggota . '-' . $pinjaman->id . '.pdf';
        return $pdf->stream($fileName);
    }
}

