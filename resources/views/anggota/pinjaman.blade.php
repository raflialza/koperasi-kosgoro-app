@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Riwayat Pinjaman Saya</h4>
        <a href="{{ route('anggota.pinjaman.ajukan') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ajukan Pinjaman Baru
        </a>
    </div>

    <div class="card shadow-sm modern-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table modern-table">
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
                                <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td>Rp{{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                                <td>{{ $pinjaman->tenor }} bulan</td>
                                <td>
                                    @php
                                        $badgeColor = 'bg-secondary';
                                        if ($pinjaman->status == 'disetujui') $badgeColor = 'bg-success';
                                        if ($pinjaman->status == 'menunggu') $badgeColor = 'bg-warning text-dark';
                                        if ($pinjaman->status == 'ditolak') $badgeColor = 'bg-danger';
                                        if ($pinjaman->status == 'lunas') $badgeColor = 'bg-info';
                                    @endphp
                                    <span class="badge {{ $badgeColor }}">{{ ucfirst($pinjaman->status) }}</span>
                                </td>
                                <td>
                                    @if($pinjaman->status == 'disetujui' || $pinjaman->status == 'lunas')
                                        <a href="{{ route('anggota.pinjaman.detail', $pinjaman->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye-fill"></i> Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Anda belum memiliki riwayat pengajuan pinjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Notifikasi Sukses dengan SweetAlert ---
    const successMessage = @json(session('success'));
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Pengajuan Berhasil!',
            text: 'Mohon tunggu konfirmasi dari admin.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Baik'
        });
    }
});
</script>
@endpush
