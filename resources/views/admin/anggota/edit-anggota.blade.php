@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Edit Data Anggota</h4>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi Kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_anggota" class="form-label">ID Anggota</label>
                    <input type="text" name="id_anggota" class="form-control" value="{{ $anggota->id_anggota }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $anggota->nama) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $anggota->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="no_telp" class="form-label">No Telepon</label>
                    <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $anggota->no_telp) }}" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="instansi" class="form-label">Instansi</label>
                    <select name="instansi" class="form-select" required>
                        <option value="">-- Pilih Instansi --</option>
                        <option value="SMP" {{ $anggota->instansi == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ $anggota->instansi == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="SMK" {{ $anggota->instansi == 'SMK' ? 'selected' : '' }}>SMK</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tahun_gabung" class="form-label">Tahun Bergabung</label>
                    <input type="text" name="tahun_gabung" class="form-control" value="{{ old('tahun_gabung', $anggota->tahun_gabung) }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@include('components.sweetalert')