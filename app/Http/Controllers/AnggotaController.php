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
    public function index(Request $request)
    {
        $query = User::where('role', 'anggota');

        // Filter pencarian nama atau email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

        // Filter instansi
        if ($request->filled('instansi')) {
            $query->where('instansi', $request->instansi);
    }

        $anggota = $query->get();

        return view('admin.anggota.kelola-anggota', compact('anggota'));
    }

    public function create()
    {
    // Ambil user dengan id_anggota tertinggi (misal: A001, A099, dst)
    $lastUser = User::where('role', 'anggota')
        ->orderBy('id_anggota', 'desc')
        ->first();

    if ($lastUser && preg_match('/AGT(\d+)/', $lastUser->id_anggota, $matches)) {
        $lastNumber = (int)$matches[1]; // Ambil angka saja
        $newId = 'AGT' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Format: GT001, AGT002, dst.
    } else {
        $newId = 'AGT001'; // Jika belum ada anggota
    }

    return view('admin.form-anggota', ['newId' => $newId]);
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
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $anggota->id,
            'no_telp'       => 'required',
            'alamat'        => 'required',
            'instansi'      => 'required|in:SMP,SMA,SMK',
            'tahun_gabung'  => 'required|digits:4',
            'password'      => 'nullable|min:6', // password boleh kosong
    ]);

        $data = $request->only([
            'nama', 'email', 'no_telp', 'alamat', 'instansi', 'tahun_gabung'
    ]);

    // Jika password diisi, update juga
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
    }

        $anggota->update($data);

    return redirect()->route('admin.anggota.index')->with('success', 'Data anggota diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

    return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }

    // app/Http/Controllers/AnggotaController.php
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $anggota = User::where('role', 'anggota')
            ->where(function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                ->orWhere('id_anggota', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->get();
        
        return view('admin.anggota.partials.list-anggota', compact('anggota'));
    }
}
