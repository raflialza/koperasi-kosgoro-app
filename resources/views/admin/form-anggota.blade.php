@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="card-title mb-4">{{ isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota' }}</h4>

            <form action="{{ isset($anggota) ? route('admin.anggota.update', $anggota->id) : route('admin.anggota.store') }}" method="POST">
                @csrf
                @if(isset($anggota))
                    @method('PUT')
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">ID Anggota</label>
                        <input type="text" name="id_anggota" class="form-control" value="{{ old('id_anggota', $newId ?? '') }}" readonly>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $anggota->nama ?? '') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $anggota->email ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" {{ isset($anggota) ? '' : 'required' }}>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No Telp</label>
                        <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $anggota->no_telp ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Instansi</label>
                        <select name="instansi" class="form-select" required>
                            <option value="">Pilih Instansi</option>
                            @foreach (['SMP', 'SMA', 'SMK'] as $instansi)
                                <option value="{{ $instansi }}" 
                                    {{ old('instansi', $anggota->instansi ?? '') === $instansi ? 'selected' : '' }}>
                                    {{ $instansi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Tahun Bergabung</label>
                    <input type="number" name="tahun_gabung" class="form-control" 
                           value="{{ old('tahun_gabung', $anggota->tahun_gabung ?? date('Y')) }}" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ isset($anggota) ? 'Update' : 'Tambah' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
