@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Manajemen Semua Pinjaman</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama</th>
                            <th>Tgl Disetujui</th>
                            <th class="text-end">Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semuaPinjaman as $pinjaman)
                            <tr>
                                <td>{{ $pinjaman->user->id_anggota }}</td>
                                <td>{{ $pinjaman->user->nama }}</td>
                                <td>{{ $pinjaman->tanggal_disetujui ? \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->format('d M Y') : '-' }}</td>
                                <td class="text-end">{{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                                <td>
                                    @if($pinjaman->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($pinjaman->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($pinjaman->status == 'lunas')
                                        <span class="badge bg-info">Lunas</span>
                                    @elseif($pinjaman->status == 'menunggu')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pinjaman->status == 'disetujui' || $pinjaman->status == 'berjalan')
                                        {{-- Mengarahkan ke halaman pembayaran --}}
                                        <a href="{{ route('admin.pinjaman.bayar', $pinjaman->id) }}" class="btn btn-sm btn-primary">Bayar Angsuran</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data pinjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection