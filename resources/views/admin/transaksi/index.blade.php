@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Semua Transaksi Koperasi</h4>
        <!-- Form Filter -->
        <form action="{{ route('admin.transaksi.semua') }}" method="GET">
            <select name="jenis" class="form-select" onchange="this.form.submit()" style="width: auto;">
                <option value="semua" {{ $jenisFilter == 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                <option value="simpanan" {{ $jenisFilter == 'simpanan' ? 'selected' : '' }}>Pemasukan Simpanan</option>
                <option value="angsuran" {{ $jenisFilter == 'angsuran' ? 'selected' : '' }}>Pembayaran Angsuran</option>
                <option value="pencairan" {{ $jenisFilter == 'pencairan' ? 'selected' : '' }}>Pencairan Pinjaman</option>
            </select>
        </form>
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
                                    @php
                                        $badgeColor = 'bg-secondary';
                                        if ($transaksi->tipe == 'simpanan') $badgeColor = 'bg-success';
                                        if ($transaksi->tipe == 'angsuran') $badgeColor = 'bg-primary';
                                        if ($transaksi->tipe == 'pencairan') $badgeColor = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $badgeColor }}">{{ $transaksi->jenis }}</span>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada transaksi yang cocok dengan filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
