<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    /**
     * Menampilkan daftar semua anggota beserta total simpanan mereka.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $query = User::where('role', 'anggota')
                     ->withSum('simpanan', 'jumlah'); // Menghitung total simpanan untuk setiap anggota

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('id_anggota', 'LIKE', "%{$search}%");
            });
        }
        
        $semuaAnggota = $query->orderBy('id_anggota', 'asc')->get();

        return view('admin.simpanan.index', compact('semuaAnggota', 'search'));
    }

    /**
     * Mengambil dan mengembalikan rincian simpanan untuk satu anggota (untuk modal).
     */
    public function show(User $user)
    {
        // Menghitung rincian simpanan per jenis
        $simpanan = $user->simpanan->groupBy('jenis_simpanan')->map(function ($group) {
            return $group->sum('jumlah');
        });

        $totalPokok = $simpanan->get('Pokok', 0);
        $totalWajib = $simpanan->get('Wajib', 0);
        $totalSukarela = $simpanan->get('Sukarela', 0);
        $totalSemua = $totalPokok + $totalWajib + $totalSukarela;

        // Mengembalikan data dalam format JSON yang akan dibaca oleh JavaScript
        return response()->json([
            'nama' => $user->nama,
            'id_anggota' => $user->id_anggota,
            'total_pokok' => 'Rp ' . number_format($totalPokok, 0, ',', '.'),
            'total_wajib' => 'Rp ' . number_format($totalWajib, 0, ',', '.'),
            'total_sukarela' => 'Rp ' . number_format($totalSukarela, 0, ',', '.'),
            'total_semua' => 'Rp ' . number_format($totalSemua, 0, ',', '.'),
        ]);
    }

    /**
     * Menampilkan form untuk menambah simpanan baru.
     * Bisa menerima ID anggota untuk pra-seleksi.
     */
    public function create(Request $request)
    {
        $anggota = User::where('role', 'anggota')->orderBy('nama', 'asc')->get();
        $selectedAnggotaId = $request->query('anggota_id'); // Mengambil ID dari URL

        return view('admin.simpanan.form-tambah', compact('anggota', 'selectedAnggotaId'));
    }

    /**
     * Menyimpan data simpanan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'jenis_simpanan'    => 'required|in:Pokok,Wajib,Sukarela',
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
}
