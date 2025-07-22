@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Transaksi Pinjaman</h5>
            <a href="{{ route('anggota.pinjaman.ajukan') }}" class="btn btn-light">
                <i class="bi bi-plus-circle me-2"></i>Ajukan Pinjaman
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengajuan</th>
                        <th class="text-end">Jumlah (Rp)</th>
                        <th>Tenor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPinjaman as $pinjaman)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }}</td>
                            <td class="text-end">{{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                            <td>{{ $pinjaman->tenor }} bulan</td>
                            <td>
                                @if($pinjaman->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($pinjaman->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($pinjaman->status == 'lunas')
                                    <span class="badge bg-info">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Menunggu</span>
                                @endif
                            </td>
                            <td> {{-- <-- Tambah sel baru --}}
                                @if($pinjaman->status != 'menunggu' && $pinjaman->status != 'ditolak')
                                    <a href="{{ route('anggota.pinjaman.detail', $pinjaman->id) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi pinjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection