<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AnggotaController extends Controller
{

    public function index(Request $request)
    {
        $query = User::where('role', 'anggota');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('instansi')) {
            $query->where('instansi', $request->instansi);
        }

        $anggota = $query->paginate(10);

        // Jika ini adalah request AJAX, kembalikan hanya bagian tabelnya
        if ($request->ajax()) {
            return view('admin.anggota.partials.list-anggota', compact('anggota'))->render();
        }

        // Jika bukan, kembalikan halaman lengkap
        return view('admin.anggota.kelola-anggota', compact('anggota'));
    }

    public function create()
    {
        return view('admin.anggota.form-anggota');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data anggota baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_telp' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'instansi' => ['required', 'string'],
            'tahun_gabung' => ['required', 'numeric'],
        ]);

        // === LOGIKA BARU UNTUK ID ANGGOTA OTOMATIS ===
        // 1. Cari anggota terakhir yang memiliki ID dengan format 'AGT...'
        $lastAnggota = User::where('id_anggota', 'like', 'AGT%')->orderBy('id_anggota', 'desc')->first();

        if ($lastAnggota) {
            // 2. Ambil nomor dari ID terakhir (misal: dari 'AGT049' menjadi 49)
            $lastNumber = (int) substr($lastAnggota->id_anggota, 3);
            // 3. Tambahkan 1 ke nomor tersebut
            $newNumber = $lastNumber + 1;
        } else {
            // 4. Jika belum ada anggota sama sekali, mulai dari 1
            $newNumber = 1;
        }

        // 5. Format ulang nomor menjadi 3 digit dengan awalan nol (misal: 50 menjadi '050')
        $newIdAnggota = 'AGT' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        // ===============================================

        User::create([
            'id_anggota' => $newIdAnggota, // Gunakan ID baru yang sudah diformat
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'role' => 'anggota',
            'instansi' => $request->instansi,
            'tahun_gabung' => $request->tahun_gabung,
        ]);

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    public function edit(User $anggota)
    {
        return view('admin.anggota.edit-anggota', compact('anggota'));
    }

    public function update(Request $request, User $anggota)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $anggota->id],
            'no_telp' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $anggota->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        if ($request->filled('password')) {
            $anggota->password = Hash::make($request->password);
            $anggota->save();
        }

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(User $anggota)
    {
         if ($anggota->pinjaman()->where('status', 'disetujui')->exists()) {
            return back()->with('error', 'Gagal menghapus. Anggota masih memiliki pinjaman aktif.');
        }
        
        $anggota->delete();

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Data anggota berhasil dihapus.');
    }
}
