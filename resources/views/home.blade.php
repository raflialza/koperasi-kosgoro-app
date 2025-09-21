@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Cek peran pengguna untuk menampilkan dasbor yang sesuai --}}
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
        
        {{-- ======================== --}}
        {{-- TAMPILAN UNTUK ADMIN     --}}
        {{-- ======================== --}}
        <div class="row mb-4">
            <div class="col">
                <h4 class="fw-bold">Selamat Datang, {{ auth()->user()->nama }}!</h4>
                <p class="text-muted">Berikut adalah ringkasan aktivitas koperasi saat ini.</p>
            </div>
        </div>

        <div class="row">
            {{-- Jumlah Anggota --}}
            <div class="col-md-3 mb-4">
                <a href="{{ route('admin.anggota.index') }}" class="text-decoration-none">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(13, 110, 253, 0.1);">
                                    <i class="bi bi-people-fill fs-4 text-primary"></i>
                                </div>
                            </div>
                            <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Jumlah Anggota</div>
                            <div class="h5 fw-bold text-dark">{{ $jumlahAnggota }}</div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Total Simpanan --}}
            <div class="col-md-3 mb-4">
                <a href="{{ route('admin.simpanan.index') }}" class="text-decoration-none">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(25, 135, 84, 0.1);">
                                    <i class="bi bi-wallet-fill fs-4 text-success"></i>
                                </div>
                            </div>
                            <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Total Simpanan</div>
                            <div class="h5 fw-bold text-dark">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Pinjaman Aktif --}}
            <div class="col-md-3 mb-4">
                <a href="{{ route('admin.pinjaman.index', ['status' => 'Disetujui']) }}" class="text-decoration-none">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(13, 202, 240, 0.1);">
                                    <i class="bi bi-cash-stack fs-4 text-info"></i>
                                </div>
                            </div>
                            <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Pinjaman Aktif</div>
                            <div class="h5 fw-bold text-dark">Rp {{ number_format($totalPinjamanAktif, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Pengajuan Menunggu --}}
            <div class="col-md-3 mb-4">
                <a href="{{ route('admin.pinjaman.index', ['status' => 'Menunggu Persetujuan']) }}" class="text-decoration-none">
                    <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(255, 193, 7, 0.1);">
                                    <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                                </div>
                            </div>
                            <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Pengajuan Menunggu</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ $pengajuanMenunggu }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @else
        
        {{-- ======================== --}}
        {{-- TAMPILAN UNTUK ANGGOTA    --}}
        {{-- ======================== --}}
        <!-- Header Sambutan -->
        <div class="row mb-4">
            <div class="col">
                <h4 class="fw-bold">Selamat Datang, {{ auth()->user()->nama }}!</h4>
                <p class="text-muted">Ini adalah ringkasan keuangan Anda di Koperasi Kosgoro.</p>
            </div>
        </div>

        <!-- Kartu Ringkasan -->
        <div class="row">
            <!-- Total Simpanan -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(25, 135, 84, 0.1);">
                                <i class="bi bi-wallet-fill fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Total Simpanan Anda</div>
                        <div class="h5 fw-bold text-dark">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                        <a href="{{ route('anggota.simpanan.riwayat') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <!-- Pinjaman Aktif -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(13, 202, 240, 0.1);">
                                <i class="bi bi-cash-stack fs-4 text-info"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Pinjaman Aktif</div>
                        @if($pinjamanAktif)
                            <div class="h5 fw-bold text-dark">Rp {{ number_format($pinjamanAktif->jumlah_pinjaman, 0, ',', '.') }}</div>
                        @else
                            <div class="h5 fw-bold text-dark">-</div>
                        @endif
                        <a href="{{ route('anggota.pinjaman.riwayat') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <!-- Sisa Tagihan -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(220, 53, 69, 0.1);">
                                <i class="bi bi-receipt-cutoff fs-4 text-danger"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Sisa Tagihan</div>
                        <div class="h5 fw-bold text-dark">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</div>
                        @if($pinjamanAktif)
                            <a href="{{ route('anggota.pinjaman.detail', $pinjamanAktif->id) }}" class="stretched-link"></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Butuh Dana Tambahan?</h5>
                        <p class="card-text text-muted">Ajukan pinjaman baru dengan proses yang mudah dan cepat.</p>
                        
                        {{-- PERBAIKAN DI SINI: Tombol selalu aktif --}}
                        <a href="{{ route('anggota.pinjaman.ajukan') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Ajukan Pinjaman Baru
                        </a>

                        {{-- Menampilkan catatan jika ada pinjaman aktif --}}
                        @if($pinjamanAktif)
                            <p class="card-text text-muted mt-2"><small>Catatan: Anda saat ini masih memiliki pinjaman yang sedang berjalan.</small></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .icon-box { display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; }
    .hover-scale { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-scale:hover { transform: scale(1.03); box-shadow: 0 1rem 3rem rgba(0,0,0,0.15) !important; }
</style>
@endpush

