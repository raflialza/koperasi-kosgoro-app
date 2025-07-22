@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Semua Transaksi Simpanan</h5>
            <div>
                <a href="{{ route('admin.laporan.simpanan') }}" class="btn btn-info btn-sm" target="_blank">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </a>
                <a href="{{ route('admin.simpanan.tambah') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Simpanan
                </a>
            </div>
        </div>
    </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Jenis Simpanan</th>
                            <th>Tanggal</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semuaSimpanan as $simpanan)
                            <tr>
                                <td>{{ $simpanan->user->id_anggota }}</td>
                                <td>{{ $simpanan->user->nama }}</td>
                                <td class="text-capitalize">{{ $simpanan->jenis_simpanan }}</td>
                                <td>{{ \Carbon\Carbon::parse($simpanan->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="text-end">{{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada transaksi simpanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection