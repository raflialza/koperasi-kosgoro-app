@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Formulir Tambah Anggota Baru</h5>
                </div>
                <div class="card-body">

                    <!-- Bagian untuk Menampilkan Error Validasi -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Terdapat kesalahan validasi:</h6>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.anggota.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="instansi" class="form-label">Instansi</label>
                                <select class="form-select" id="instansi" name="instansi" required>
                                    <option value="SMP" {{ old('instansi') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('instansi') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="SMK" {{ old('instansi') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_gabung" class="form-label">Tahun Gabung</label>
                                <input type="number" class="form-control" id="tahun_gabung" name="tahun_gabung" value="{{ old('tahun_gabung', date('Y')) }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Anggota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
