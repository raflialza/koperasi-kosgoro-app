@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Riwayat Pinjaman Saya</h4>
        <a href="{{ route('anggota.pinjaman.ajukan') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajukan Pinjaman Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <th>Jumlah Pinjaman</th>
                            <th>Tenor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarPinjaman as $pinjaman)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                <td>Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                                <td>{{ $pinjaman->tenor }} bulan</td>
                                <td>
                                    <span class="badge 
                                        @if($pinjaman->status == 'Disetujui') bg-success 
                                        @elseif($pinjaman->status == 'Lunas') bg-info
                                        @elseif($pinjaman->status == 'Menunggu Persetujuan') bg-warning text-dark
                                        @elseif($pinjaman->status == 'Ditolak') bg-danger
                                        @endif">
                                        {{ $pinjaman->status }}
                                    </span>
                                </td>
                                <td>
                                    {{-- TOMBOL BARU UNTUK MELIHAT DETAIL --}}
                                    <a href="{{ route('anggota.pinjaman.detail', $pinjaman->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Anda belum memiliki riwayat pinjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
