@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Detail Pinjaman</h4>
                <div>
                    <a href="{{ route('anggota.pinjaman.riwayat') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('anggota.pinjaman.invoice', $pinjaman->id) }}" class="btn btn-success" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Invoice
                    </a>
                </div>
            </div>

            <!-- Informasi Utama Pinjaman -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ID Pinjaman: #{{ str_pad($pinjaman->id, 5, '0', STR_PAD_LEFT) }}</h5>
                    <span class="badge 
                        @if($pinjaman->status == 'Disetujui') bg-success 
                        @elseif($pinjaman->status == 'Lunas') bg-info
                        @elseif($pinjaman->status == 'Menunggu Persetujuan') bg-warning text-dark
                        @elseif($pinjaman->status == 'Ditolak') bg-danger
                        @endif fs-6">
                        {{ $pinjaman->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal Pengajuan:</strong> {{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->translatedFormat('d F Y') }}</p>
                            <p><strong>Tanggal Disetujui:</strong> {{ $pinjaman->tanggal_disetujui ? \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->translatedFormat('d F Y') : '-' }}</p>
                            <p><strong>Keterangan:</strong> {{ $pinjaman->keterangan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pinjaman Pokok:</strong> Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</p>
                            <p><strong>Margin:</strong> {{ $pinjaman->margin }}%</p>
                            <p><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Keuangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Rincian Keuangan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Tagihan
                            <span class="fw-bold">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Angsuran per Bulan
                            <span class="fw-bold">Rp {{ number_format($angsuranPerBulan, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-success">
                            Total Terbayar
                            <span class="fw-bold">Rp {{ number_format($pinjaman->angsuran->sum('jumlah_bayar'), 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                            Sisa Tagihan
                            <span class="fw-bold">Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Riwayat Angsuran -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                     <h5 class="mb-0">Riwayat Pembayaran Angsuran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Angsuran Ke-</th>
                                    <th>Tanggal Bayar</th>
                                    <th class="text-end">Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pinjaman->angsuran as $angsuran)
                                    <tr>
                                        <td>{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->translatedFormat('d F Y') }}</td>
                                        <td class="text-end">Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada riwayat pembayaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
