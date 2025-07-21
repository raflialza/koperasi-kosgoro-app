<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Simpanan;
use App\Models\Pinjaman;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = User::where('role', 'anggota')->get();
        return view('admin.kelola-anggota', compact('anggota'));
    }

    public function create()
    {
        return view('admin.form-anggota');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|unique:users',
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'no_telp'    => 'required',
            'alamat'     => 'required',
            'instansi'   => 'required|in:SMP,SMA,SMK',
            'tahun_gabung' => 'required|digits:4',
        ]);

        User::create([
            'id_anggota'    => $request->id_anggota,
            'nama'          => $request->nama,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => 'anggota',
            'no_telp'       => $request->no_telp,
            'alamat'        => $request->alamat,
            'instansi'      => $request->instansi,
            'tahun_gabung'  => $request->tahun_gabung,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $anggota)
    {
        return view('admin.form-anggota', compact('anggota'));
    }

    public function update(Request $request, User $anggota)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $anggota->id,
            'no_telp'    => 'required',
            'alamat'     => 'required',
            'instansi'   => 'required|in:SMP,SMA,SMK',
            'tahun_gabung' => 'required|digits:4',
        ]);

        $anggota->update($request->only([
            'nama', 'email', 'no_telp', 'alamat', 'instansi', 'tahun_gabung'
        ]));

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

    return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
