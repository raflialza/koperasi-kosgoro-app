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
        // Ambil input dari search dan filter
        $search = $request->query('search', '');
        $instansi = $request->query('instansi', '');

        $query = User::where('role', 'anggota');

        // Terapkan filter pencarian teks
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhere('id_anggota', 'LIKE', "%{$search}%") 
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Terapkan filter instansi
        if (!empty($instansi)) {
            $query->whereRaw('LOWER(instansi) = ?', [strtolower($instansi)]);
        }

        $anggota = $query->orderBy('id_anggota', 'asc')->get();

        // Jika ini adalah permintaan AJAX (untuk live search)
        if ($request->ajax()) {
            return view('admin.anggota.partials.list-anggota', compact('anggota'))->render();
        }

        // Jika tidak, kirimkan halaman lengkap
        return view('admin.anggota.kelola-anggota', compact('anggota', 'search', 'instansi'));
    }
    
    public function create()
    {
        return view('admin.anggota.form-anggota');
    }

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

        $lastAnggota = User::where('id_anggota', 'like', 'AGT%')->orderBy('id_anggota', 'desc')->first();

        if ($lastAnggota) {
            $lastNumber = (int) substr($lastAnggota->id_anggota, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newIdAnggota = 'AGT' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        User::create([
            'id_anggota' => $newIdAnggota,
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

    /**
     * PERUBAHAN FINAL: Mencari anggota secara manual.
     * Menerima $id_anggota sebagai string dan mencari user di database.
     * firstOrFail() akan otomatis menampilkan 404 jika user tidak ditemukan.
     */
    public function edit(string $id_anggota)
    {
        $anggota = User::where('id_anggota', $id_anggota)->firstOrFail();
        return view('admin.anggota.edit-anggota', compact('anggota'));
    }

    /**
     * PERUBAHAN FINAL: Mencari anggota secara manual sebelum update.
     */
    public function update(Request $request, string $id_anggota)
    {
        $anggota = User::where('id_anggota', $id_anggota)->firstOrFail();

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

    /**
     * PERUBAHAN FINAL: Mencari anggota secara manual sebelum menghapus.
     */
    public function destroy(string $id_anggota)
    {
        $anggota = User::where('id_anggota', $id_anggota)->firstOrFail();

        if ($anggota->pinjaman()->where('status', 'disetujui')->exists()) {
            return back()->with('error', 'Gagal menghapus. Anggota masih memiliki pinjaman aktif.');
        }
        
        $anggota->delete();

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Data anggota berhasil dihapus.');
    }
}

