@extends('layouts.app')

@section('content')
<div class="container py-4">
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
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale card-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(13, 110, 253, 0.1);">
                                <i class="bi bi-people-fill fs-4 text-primary"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Jumlah Anggota</div>
                        <div class="h5 fw-bold text-dark">{{ $jumlahAnggota }}</div>
                    </div>
                    <div class="card-line-bottom bg-primary"></div>
                </div>
            </a>
        </div>

        {{-- Total Simpanan --}}
        <div class="col-md-3 mb-4">
            <a href="{{ route('admin.simpanan.index') }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale card-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(25, 135, 84, 0.1);">
                                <i class="bi bi-wallet-fill fs-4 text-success"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Total Simpanan</div>
                        <div class="h5 fw-bold text-dark">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                    </div>
                    <div class="card-line-bottom bg-success"></div>
                </div>
            </a>
        </div>

        {{-- Pinjaman Aktif --}}
        <div class="col-md-3 mb-4">
            <a href="{{ route('admin.pinjaman.index', ['status' => 'Disetujui']) }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale card-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(13, 202, 240, 0.1);">
                                <i class="bi bi-cash-stack fs-4 text-info"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Pinjaman Aktif</div>
                        <div class="h5 fw-bold text-dark">Rp {{ number_format($totalPinjamanAktif, 0, ',', '.') }}</div>
                    </div>
                    <div class="card-line-bottom bg-info"></div>
                </div>
            </a>
        </div>

        {{-- Pengajuan Menunggu --}}
        <div class="col-md-3 mb-4">
            {{-- PERBAIKAN: Tautan disesuaikan ke route yang benar --}}
            <a href="{{ route('admin.pinjaman.index', ['status' => 'Menunggu Persetujuan']) }}" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-2 hover-scale card-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box rounded-3 p-2 me-3" style="background-color: rgba(255, 193, 7, 0.1);">
                                <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                            </div>
                        </div>
                        <div class="card-title text-muted fw-bold mb-1" style="font-size: 0.9rem;">Pengajuan Menunggu</div>
                        <div class="h5 mb-0 fw-bold text-dark">{{ $pengajuanMenunggu }}</div>
                    </div>
                    <div class="card-line-bottom bg-warning"></div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .rounded-4 { border-radius: 1rem !important; }
    .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
    }
    .card {
        position: relative;
        overflow: hidden;
    }
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.03);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.15) !important;
    }
    .card-line-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }
    .card:hover .card-line-bottom {
        opacity: 1;
    }
</style>
@endpush
