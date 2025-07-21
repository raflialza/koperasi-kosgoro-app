{{-- resources/views/admin/form-anggota.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">{{ isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota' }}</h2>

    <form action="{{ route('admin.anggota.store') }}" method="POST">
        @csrf
        @if(isset($anggota))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label>ID Anggota</label>
            <input type="text" name="id_anggota" class="w-full border px-3 py-2" value="{{ old('id_anggota', $anggota->id_anggota ?? '') }}" required>
        </div>

        <div class="mb-4">
            <label>Nama</label>
            <input type="text" name="nama" class="w-full border px-3 py-2" value="{{ old('nama', $anggota->nama ?? '') }}" required>
        </div>

        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" class="w-full border px-3 py-2" value="{{ old('email', $anggota->email ?? '') }}" required>
        </div>

        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2" {{ isset($anggota) ? '' : 'required' }}>
        </div>

        <div class="mb-4">
            <label>No Telp</label>
            <input type="text" name="no_telp" class="w-full border px-3 py-2" value="{{ old('no_telp', $anggota->no_telp ?? '') }}" required>
        </div>

        <div class="mb-4">
            <label>Alamat</label>
            <textarea name="alamat" class="w-full border px-3 py-2" required>{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label>Instansi</label>
            <select name="instansi" class="w-full border px-3 py-2" required>
                <option value="">Pilih Instansi</option>
                @foreach (['SMP', 'SMA', 'SMK'] as $instansi)
                    <option value="{{ $instansi }}" {{ old('instansi', $anggota->instansi ?? '') === $instansi ? 'selected' : '' }}>{{ $instansi }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label>Tahun Bergabung</label>
            <input type="number" name="tahun_gabung" class="w-full border px-3 py-2" value="{{ old('tahun_gabung', $anggota->tahun_gabung ?? date('Y')) }}" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ isset($anggota) ? 'Update' : 'Tambah' }}
        </button>
    </form>
</div>
@endsection
