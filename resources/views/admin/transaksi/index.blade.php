@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Semua Transaksi Koperasi</h4>
    </div>

    <div class="card shadow-sm modern-card">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 650px; overflow-y: auto;">
                <table class="table modern-table">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Anggota</th>
                            <th>Jenis Transaksi</th>
                            <th class="text-end">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semuaTransaksi as $transaksi)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                                <td>{{ $transaksi->nama_anggota }}</td>
                                <td>
                                    {{-- Logika untuk Badge Berwarna --}}
                                    @if(str_contains($transaksi->jenis, 'Simpanan'))
                                        <span class="badge bg-success">{{ $transaksi->jenis }}</span>
                                    @elseif($transaksi->jenis == 'Angsuran Pinjaman')
                                        <span class="badge bg-primary">{{ $transaksi->jenis }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $transaksi->jenis }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada transaksi yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
