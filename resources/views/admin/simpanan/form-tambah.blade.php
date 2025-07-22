@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Tambah Simpanan Anggota</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.simpanan.proses-tambah') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih Anggota</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Pilih Anggota --</option>
                                @foreach($anggota as $user)
                                    <option value="{{ $user->id }}">{{ $user->id_anggota }} - {{ $user->nama }}</option>
                                @endforeach
                            </select>
                             @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jenis_simpanan" class="form-label">Jenis Simpanan</label>
                            <select class="form-select @error('jenis_simpanan') is-invalid @enderror" id="jenis_simpanan" name="jenis_simpanan" required>
                                <option value="pokok">Simpanan Pokok</option>
                                <option value="wajib">Simpanan Wajib</option>
                            </select>
                             @error('jenis_simpanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" required>
                             @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required>
                             @error('tanggal_transaksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.simpanan.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection