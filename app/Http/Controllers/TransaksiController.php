<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // ====== UNTUK ANGGOTA ======

    public function lihatSimpanan()
    {
        $daftarSimpanan = Simpanan::where('user_id', Auth::id())->latest()->get();
        return view('anggota.simpanan', compact('daftarSimpanan'));
    }

    public function lihatPinjaman()
    {
        $daftarPinjaman = Pinjaman::where('user_id', Auth::id())->latest()->get();
        return view('anggota.pinjaman', compact('daftarPinjaman'));
    }

    public function detailPinjamanAnggota($id)
    {
        // 1. Ambil data pinjaman, tapi pastikan pinjaman ini milik user yang sedang login
        $pinjaman = Pinjaman::with('user', 'angsuran')
                            ->where('user_id', Auth::id()) // <-- Pengecekan keamanan penting!
                            ->findOrFail($id);
        
        // 2. Hitung sisa pinjaman dan data lainnya (logika yang sama dengan admin)
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $sisaPinjaman = $pinjaman->jumlah_pinjaman - $totalTerbayar;
        $angsuranPerBulan = $pinjaman->jumlah_pinjaman / $pinjaman->tenor;

        // 3. Kirim data ke view baru
        return view('anggota.detail-pinjaman', compact('pinjaman', 'sisaPinjaman', 'angsuranPerBulan'));
    }

    public function ajukanPinjamanForm()
    {
        return view('anggota.ajukan-pinjaman');
    }

    public function prosesAjukanPinjaman(Request $request)
    {
        $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:100000',
            'tenor'           => 'required|integer|min:1',
            'keperluan'       => 'required|string|max:255',
        ]);

        Pinjaman::create([
            'user_id'           => Auth::id(),
            'jumlah_pinjaman'   => $request->jumlah_pinjaman,
            'tenor'             => $request->tenor,
            'keperluan'         => $request->keperluan,
            'tanggal_pengajuan' => now(),
            'status'            => 'menunggu',
        ]);

        return redirect()->route('anggota.pinjaman.riwayat')
                         ->with('success', 'Pengajuan pinjaman Anda berhasil dikirim!');
    }

    // ====== UNTUK ADMIN ======

    public function daftarPengajuanPinjaman()
    {
        // Ambil semua data pinjaman yang statusnya 'menunggu'
        // 'with('user')' digunakan untuk mengambil data user terkait (relasi)
        $daftarPengajuan = Pinjaman::with('user')
                                ->where('status', 'menunggu')
                                ->latest('tanggal_pengajuan')
                                ->get();

        return view('admin.pinjaman.daftar-pengajuan', compact('daftarPengajuan'));
    }

    public function prosesPengajuan(Request $request, $id)
    {
        // 1. Validasi input dari admin
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        // 2. Cari pinjaman berdasarkan ID
        $pinjaman = Pinjaman::findOrFail($id);

        // 3. Update status pinjaman
        $pinjaman->status = $request->status;
        $pinjaman->approved_by = Auth::id(); // Catat siapa admin yang memproses

        // Jika disetujui, catat tanggal persetujuannya
        if ($request->status == 'disetujui') {
            $pinjaman->tanggal_disetujui = now();
        }

        $pinjaman->save();

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.pinjaman.pengajuan')
                        ->with('success', 'Status pengajuan pinjaman berhasil diperbarui!');
    }

        public function daftarSemuaSimpanan()
    {
        // Ambil semua data simpanan dari semua anggota, diurutkan dari yang terbaru
        $semuaSimpanan = Simpanan::with('user')->latest()->get();

        return view('admin.simpanan.index', compact('semuaSimpanan'));
    }

    public function tambahSimpananForm()
    {
        // Ambil semua user dengan role 'anggota' untuk ditampilkan di dropdown
        $anggota = User::where('role', 'anggota')->get();

        return view('admin.simpanan.form-tambah', compact('anggota'));
    }

    public function prosesTambahSimpanan(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'jenis_simpanan'    => 'required|in:pokok,wajib,sukarela',
            'jumlah'            => 'required|numeric|min:1000',
            'tanggal_transaksi' => 'required|date',
        ]);

        // 2. Simpan ke database
        Simpanan::create([
            'user_id'           => $request->user_id,
            'jenis_simpanan'    => $request->jenis_simpanan,
            'jumlah'            => $request->jumlah,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'processed_by'      => Auth::id(), // Catat siapa admin yang memproses
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.simpanan.index')
                        ->with('success', 'Transaksi simpanan berhasil ditambahkan!');
    }

        public function cetakLaporanSimpanan()
    {
        // 1. Ambil semua data simpanan
        $semuaSimpanan = Simpanan::with('user')->orderBy('tanggal_transaksi', 'desc')->get();
        
        // 2. Kelompokkan data berdasarkan jenis simpanan
        $data = [
            'semuaSimpanan' => $semuaSimpanan,
            'totalPokok' => $semuaSimpanan->where('jenis_simpanan', 'pokok')->sum('jumlah'),
            'totalWajib' => $semuaSimpanan->where('jenis_simpanan', 'wajib')->sum('jumlah'),
            'totalSukarela' => $semuaSimpanan->where('jenis_simpanan', 'sukarela')->sum('jumlah'),
            'totalSemua' => $semuaSimpanan->sum('jumlah')
        ];

        // 3. Buat PDF dari view
        $pdf = Pdf::loadView('admin.laporan.pdf-simpanan', $data);
        
        // 4. Tampilkan atau unduh PDF
        return $pdf->stream('laporan-semua-simpanan.pdf');
    }

    public function cetakLaporanPinjaman()
    {
        // 1. Ambil semua data pinjaman
        $semuaPinjaman = Pinjaman::with('user')->orderBy('tanggal_pengajuan', 'desc')->get();
        
        // 2. Kelompokkan data
        $data = [
            'semuaPinjaman' => $semuaPinjaman,
            'totalPinjaman' => $semuaPinjaman->where('status', '!=', 'ditolak')->sum('jumlah_pinjaman'),
            'pinjamanDisetujui' => $semuaPinjaman->where('status', 'disetujui')->count(),
            'pinjamanLunas' => $semuaPinjaman->where('status', 'lunas')->count(),
        ];

        // 3. Buat PDF dari view
        $pdf = Pdf::loadView('admin.laporan.pdf-pinjaman', $data);

        // 4. Tampilkan atau unduh PDF
        return $pdf->stream('laporan-semua-pinjaman.pdf');
    }

    public function daftarSemuaPinjamanAdmin()
    {
        // Ambil semua data pinjaman, tidak hanya yang menunggu
        $semuaPinjaman = Pinjaman::with('user')->latest('tanggal_pengajuan')->get();

        return view('admin.pinjaman.index', compact('semuaPinjaman'));
    }

    public function pembayaranAngsuranForm($id)
{
    // Ambil data pinjaman beserta relasi user dan angsuran
    $pinjaman = Pinjaman::with('user', 'angsuran')->findOrFail($id);
    
    // Hitung sisa pinjaman dan data lainnya
    $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
    $sisaPinjaman = $pinjaman->jumlah_pinjaman - $totalTerbayar;
    $angsuranPerBulan = $pinjaman->jumlah_pinjaman / $pinjaman->tenor;
    $angsuranKe = $pinjaman->angsuran->count() + 1;

    return view('admin.pinjaman.pembayaran', compact('pinjaman', 'sisaPinjaman', 'angsuranPerBulan', 'angsuranKe'));
}

    public function prosesPembayaranAngsuran(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
        ]);

        $pinjaman = Pinjaman::with('angsuran')->findOrFail($id);

        // 2. Simpan data angsuran baru
        Angsuran::create([
            'pinjaman_id'   => $pinjaman->id,
            'jumlah_bayar'  => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'angsuran_ke'   => $pinjaman->angsuran->count() + 1,
            'processed_by'  => Auth::id(),
        ]);

        // 3. Cek apakah pinjaman sudah lunas
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar') + $request->jumlah_bayar;
        if ($totalTerbayar >= $pinjaman->jumlah_pinjaman) {
            $pinjaman->status = 'lunas';
            $pinjaman->save();
        }

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.pinjaman.bayar', $pinjaman->id)
                        ->with('success', 'Pembayaran angsuran berhasil dicatat!');
    }
}

