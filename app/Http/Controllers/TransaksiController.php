<?php

namespace App\Http\Controllers;

// --- Imports ---
use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Fungsi untuk Role ANGGOTA
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman riwayat simpanan milik anggota yang login.
     */
    public function lihatSimpanan()
    {
        $daftarSimpanan = Simpanan::where('user_id', Auth::id())->latest()->get();
        return view('anggota.simpanan', compact('daftarSimpanan'));
    }

    /**
     * Menampilkan halaman riwayat pinjaman dan status kelayakan meminjam.
     */
    public function lihatPinjaman()
    {
        $daftarPinjaman = Pinjaman::where('user_id', Auth::id())->latest()->get();
        return view('anggota.pinjaman', compact('daftarPinjaman'));
    }

    /**
     * Menampilkan detail spesifik dari satu pinjaman milik anggota.
     */
    public function detailPinjamanAnggota($id)
    {
        $pinjaman = Pinjaman::with('user', 'angsuran')
                            ->where('user_id', Auth::id()) // Keamanan: pastikan pinjaman milik user
                            ->findOrFail($id);
        
        // Logika perhitungan yang aman untuk data lama/baru
        $totalTagihan = $pinjaman->total_tagihan > 0 ? $pinjaman->total_tagihan : $pinjaman->jumlah_pinjaman;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $totalTagihan - $totalTerbayar;
        $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;

        return view('anggota.detail-pinjaman', compact('pinjaman', 'sisaPinjaman', 'angsuranPerBulan'));
    }

    /**
     * Menampilkan form untuk mengajukan pinjaman baru.
     */
    public function ajukanPinjamanForm()
    {
        return view('anggota.ajukan-pinjaman');
    }

    /**
     * Memproses dan menyimpan pengajuan pinjaman baru dari anggota.
     */
    public function prosesAjukanPinjaman(Request $request)
    {
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'tenor'           => 'required|integer|min:1',
            'keperluan'       => 'required|string|max:255',
        ]);

        $pokok = $request->jumlah_pinjaman;
        $bunga = 0.10; // Bunga 10%
        $totalTagihan = $pokok + ($pokok * $bunga);

        Pinjaman::create([
            'user_id'           => Auth::id(),
            'jumlah_pinjaman'   => $pokok,
            'persentase_bunga'  => $bunga,
            'total_tagihan'     => $totalTagihan,
            'tenor'             => $request->tenor,
            'keperluan'         => $request->keperluan,
            'tanggal_pengajuan' => now(),
            'status'            => 'menunggu',
        ]);

        return redirect()->route('anggota.pinjaman.riwayat')
                         ->with('success', 'Pengajuan pinjaman Anda berhasil dikirim!');
    }


    /*
    |--------------------------------------------------------------------------
    | Fungsi untuk Role ADMIN & SUPER ADMIN
    |--------------------------------------------------------------------------
    */

    // --- KELOLA SIMPANAN ---

    /**
     * Menampilkan daftar semua transaksi simpanan dari semua anggota.
     */
    public function daftarSemuaSimpanan()
    {
        $semuaSimpanan = Simpanan::with('user')->latest()->get();
        return view('admin.simpanan.index', compact('semuaSimpanan'));
    }

    /**
     * Menampilkan form untuk menambah simpanan baru untuk seorang anggota.
     */
    public function tambahSimpananForm()
    {
        $anggota = User::where('role', 'anggota')->get();
        return view('admin.simpanan.form-tambah', compact('anggota'));
    }

    /**
     * Memproses dan menyimpan data simpanan baru yang diinput oleh Admin.
     */
    public function prosesTambahSimpanan(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'jenis_simpanan'    => 'required|in:Pokok,Wajib,Sukarela', // Diperbaiki ke Huruf Kapital
            'jumlah'            => 'required|numeric|min:1000',
            'tanggal_transaksi' => 'required|date',
        ]);

        Simpanan::create([
            'user_id'           => $request->user_id,
            'jenis_simpanan'    => $request->jenis_simpanan,
            'jumlah'            => $request->jumlah,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'processed_by'      => Auth::id(),
        ]);

        return redirect()->route('admin.simpanan.index')
                         ->with('success', 'Transaksi simpanan berhasil ditambahkan!');
    }


    // --- KELOLA PINJAMAN & ANGSURAN ---

    /**
     * Menampilkan daftar pengajuan pinjaman yang statusnya 'menunggu'.
     */
    public function daftarPengajuanPinjaman()
    {
        $daftarPengajuan = Pinjaman::with('user')
                                ->where('status', 'menunggu')
                                ->latest('tanggal_pengajuan')
                                ->get(); // <-- Pastikan ini .get()

        return view('admin.pinjaman.daftar-pengajuan', compact('daftarPengajuan'));
    }

    /**
     * Menampilkan daftar semua pinjaman (menunggu, disetujui, lunas, dll).
     */
    public function daftarSemuaPinjamanAdmin(Request $request)
    {
        // 1. Ambil status dari URL, default-nya adalah 'aktif'
        $status = $request->query('status', 'aktif');

        // 2. Buat query dasar
        $query = Pinjaman::with('user')->latest('tanggal_pengajuan');

        // 3. Terapkan filter berdasarkan status
        switch ($status) {
            case 'aktif':
                $query->whereIn('status', ['disetujui', 'berjalan']);
                break;
            case 'lunas':
                $query->where('status', 'lunas');
                break;
            case 'menunggu':
                $query->where('status', 'menunggu');
                break;
            case 'ditolak':
                $query->where('status', 'ditolak');
                break;
        }

        // 4. Ambil datanya
        $semuaPinjaman = $query->get();

        // 5. Kirim data yang sudah difilter dan status aktif ke view
        return view('admin.pinjaman.index', compact('semuaPinjaman', 'status'));
    }

    // app/Http/Controllers/TransaksiController.php

    public function searchSemuaPinjaman(Request $request)
    {
        // 1. Ambil kata kunci dan status dari permintaan
        $query = $request->get('query', '');
        $status = $request->get('status', 'aktif');

        // 2. Buat query dasar
        $pinjamanQuery = Pinjaman::with('user')->latest('tanggal_pengajuan');

        // 3. Terapkan filter status berdasarkan tab yang aktif
        switch ($status) {
            case 'aktif':
                $pinjamanQuery->whereIn('status', ['disetujui', 'berjalan']);
                break;
            case 'lunas':
                $pinjamanQuery->where('status', 'lunas');
                break;
            case 'menunggu':
                $pinjamanQuery->where('status', 'menunggu');
                break;
            case 'ditolak':
                $pinjamanQuery->where('status', 'ditolak');
                break;
        }
        
        // 4. Terapkan filter pencarian jika ada kata kunci
        if (!empty($query)) {
            $pinjamanQuery->whereHas('user', function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                ->orWhere('id_anggota', 'LIKE', "%{$query}%");
            });
        }

        // 5. Ambil hasilnya
        $semuaPinjaman = $pinjamanQuery->get();
        
        // 6. Kembalikan hasilnya ke view partial
        return view('admin.pinjaman.partials.list-semua-pinjaman', compact('semuaPinjaman', 'status'));
    }
    
    /**
     * Memproses persetujuan atau penolakan pengajuan pinjaman.
     */
    public function prosesPengajuan(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);
        $pinjaman = Pinjaman::findOrFail($id);
        $pinjaman->status = $request->status;
        $pinjaman->approved_by = Auth::id();

        if ($request->status == 'disetujui') {
            $pinjaman->tanggal_disetujui = now();
        }

        $pinjaman->save();
        return redirect()->route('admin.pinjaman.pengajuan')
                         ->with('success', 'Status pengajuan pinjaman berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman detail pinjaman untuk pembayaran angsuran.
     */
    public function pembayaranAngsuranForm($id)
{
    $pinjaman = Pinjaman::with('user', 'angsuran')->findOrFail($id);
    
    // (PENGAMAN) Blokir akses jika pinjaman belum disetujui
    if ($pinjaman->status == 'menunggu' || $pinjaman->status == 'ditolak') {
        return redirect()->route('admin.pinjaman.semua', ['status' => $pinjaman->status])
                         ->with('error', 'Pinjaman ini belum disetujui dan tidak bisa dibayar.');
    }
    
    $totalTagihan = $pinjaman->total_tagihan > 0 ? $pinjaman->total_tagihan : $pinjaman->jumlah_pinjaman;
    $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
    $sisaPinjaman = $totalTagihan - $totalTerbayar;
    $angsuranPerBulan = $pinjaman->tenor > 0 ? $totalTagihan / $pinjaman->tenor : 0;
    $angsuranKe = $pinjaman->angsuran->count() + 1;

    return view('admin.pinjaman.pembayaran', compact('pinjaman', 'sisaPinjaman', 'angsuranPerBulan', 'angsuranKe'));
}
    
    /**
     * Memproses dan menyimpan pembayaran angsuran baru.
     */
    public function prosesPembayaranAngsuran(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
        ]);

        $pinjaman = Pinjaman::with('angsuran')->findOrFail($id);

        // --- (TAMBAHAN BARU) Blokir proses jika pinjaman belum disetujui ---
        if ($pinjaman->status == 'menunggu' || $pinjaman->status == 'ditolak') {
            return redirect()->route('admin.pinjaman.semua')
                            ->with('error', 'Gagal memproses. Pinjaman ini belum disetujui.');
        }

        Angsuran::create([
            'pinjaman_id'   => $pinjaman->id,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'angsuran_ke'   => $pinjaman->angsuran->count() + 1,
            'processed_by'  => Auth::id(),
        ]);
        
        $totalTagihan = $pinjaman->total_tagihan > 0 ? $pinjaman->total_tagihan : $pinjaman->jumlah_pinjaman;
        $totalTerbayar = $pinjaman->fresh()->angsuran->sum('jumlah_bayar');

        if ($totalTerbayar >= $totalTagihan) {
            $pinjaman->status = 'lunas';
            $pinjaman->save();
        }

        return redirect()->route('admin.pinjaman.bayar', $pinjaman->id)
                        ->with('success', 'Pembayaran angsuran berhasil dicatat!');
    }

    public function prosesPembayaranMassal(Request $request)
    {
        // 1. Validasi: pastikan ada pinjaman yang dipilih
        $request->validate([
            'pinjaman_ids' => 'required|array|min:1',
            'pinjaman_ids.*' => 'exists:pinjaman,id',
        ]);

        $berhasil = 0;
        $gagal = 0;

        // 2. Lakukan perulangan untuk setiap ID pinjaman yang dipilih
        foreach ($request->pinjaman_ids as $pinjamanId) {
            $pinjaman = Pinjaman::with('angsuran')->find($pinjamanId);

            // Pastikan pinjaman masih aktif
            if ($pinjaman && ($pinjaman->status == 'disetujui' || $pinjaman->status == 'berjalan')) {
                $totalTagihan = $pinjaman->total_tagihan > 0 ? $pinjaman->total_tagihan : $pinjaman->jumlah_pinjaman;
                $angsuranPerBulan = $pinjaman->tenor > 0 ? round($totalTagihan / $pinjaman->tenor) : 0;
                
                // Buat satu catatan angsuran
                Angsuran::create([
                    'pinjaman_id'   => $pinjaman->id,
                    'jumlah_bayar'  => $angsuranPerBulan,
                    'tanggal_bayar' => now(),
                    'angsuran_ke'   => $pinjaman->angsuran->count() + 1,
                    'processed_by'  => Auth::id(),
                ]);

                // Cek ulang status lunas
                $pinjaman->refresh();
                $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
                if ($totalTerbayar >= $totalTagihan) {
                    $pinjaman->status = 'lunas';
                    $pinjaman->save();
                }
                $berhasil++;
            } else {
                $gagal++;
            }
        }

        return redirect()->route('admin.pinjaman.semua')
                        ->with('success', "$berhasil angsuran berhasil dibayar. $gagal pinjaman dilewati (sudah lunas/status tidak valid).");
    }
    // --- LAPORAN PDF ---

    /**
     * Mencetak laporan PDF untuk semua transaksi simpanan.
     */
    public function cetakLaporanSimpanan()
    {
        $semuaSimpanan = Simpanan::with('user')->orderBy('tanggal_transaksi', 'desc')->get();
        $data = [
            'semuaSimpanan' => $semuaSimpanan,
            'totalPokok' => $semuaSimpanan->where('jenis_simpanan', 'Pokok')->sum('jumlah'),
            'totalWajib' => $semuaSimpanan->where('jenis_simpanan', 'Wajib')->sum('jumlah'),
            'totalSukarela' => $semuaSimpanan->where('jenis_simpanan', 'Sukarela')->sum('jumlah'),
            'totalSemua' => $semuaSimpanan->sum('jumlah')
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-simpanan', $data);
        return $pdf->stream('laporan-semua-simpanan.pdf');
    }

    // app/Http/Controllers/TransaksiController.php
    public function searchSemuaSimpanan(Request $request)
    {
        $query = $request->get('query', '');
        $semuaSimpanan = Simpanan::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                ->orWhere('id_anggota', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->get();
        
        return view('admin.simpanan.partials.list-simpanan', compact('semuaSimpanan'));
    }

    /**
     * Mencetak laporan PDF untuk semua transaksi pinjaman.
     */
    public function cetakLaporanPinjaman()
    {
        $semuaPinjaman = Pinjaman::with('user')->orderBy('tanggal_pengajuan', 'desc')->get();
        $data = [
            'semuaPinjaman' => $semuaPinjaman,
            'totalPinjaman' => $semuaPinjaman->where('status', '!=', 'ditolak')->sum('total_tagihan'), // Diperbaiki
            'pinjamanDisetujui' => $semuaPinjaman->where('status', 'disetujui')->count(),
            'pinjamanLunas' => $semuaPinjaman->where('status', 'lunas')->count(),
        ];
        $pdf = Pdf::loadView('admin.laporan.pdf-pinjaman', $data);
        return $pdf->stream('laporan-semua-pinjaman.pdf');
    }

    public function searchPengajuan(Request $request)
{
    $query = $request->get('query', '');

    // Jika query kosong, kembalikan semua pengajuan
    if (empty($query)) {
        $daftarPengajuan = Pinjaman::with('user')->where('status', 'menunggu')->latest('tanggal_pengajuan')->get();
    } else {
        // Jika ada query, cari yang cocok
        $daftarPengajuan = Pinjaman::with('user')
            ->where('status', 'menunggu')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                  ->orWhere('id_anggota', 'LIKE', "%{$query}%");
            })
            ->get();
    }
    
    // Kembalikan hasilnya ke view partial
    return view('admin.pinjaman.partials.list-pengajuan', compact('daftarPengajuan'));
}
}