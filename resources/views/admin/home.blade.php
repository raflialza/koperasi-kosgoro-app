@extends('layouts.app')

@section('content')
<div class="container">
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
                <div class="card shadow-sm border-start-primary h-100 hover-shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Jumlah Anggota</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $jumlahAnggota }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                            </div>
                        </div>
                        {{-- Progress Bar Anggota --}}
                        <div class="mt-3">
                            @php
                                $maxAnggota = 50;
                                $progressAnggota = ($jumlahAnggota / $maxAnggota) * 100;
                            @endphp
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progressAnggota }}%" aria-valuenow="{{ $jumlahAnggota }}" aria-valuemin="0" aria-valuemax="{{ $maxAnggota }}"></div>
                            </div>
                            <div class="text-end text-muted mt-1" style="font-size: 0.8rem;">
                                {{ number_format($progressAnggota, 0) }}% dari {{ $maxAnggota }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Simpanan --}}
        <div class="col-md-3 mb-4">
            <a href="{{ route('admin.simpanan.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-start-success h-100 hover-shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Simpanan</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-wallet-fill fs-2 text-gray-300"></i>
                            </div>
                        </div>
                        {{-- Progress Bar Simpanan --}}
                        <div class="mt-3">
                            @php
                                $maxSimpanan = 50000000;
                                $progressSimpanan = ($totalSimpanan / $maxSimpanan) * 100;
                            @endphp
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressSimpanan }}%" aria-valuenow="{{ $totalSimpanan }}" aria-valuemin="0" aria-valuemax="{{ $maxSimpanan }}"></div>
                            </div>
                            <div class="text-end text-muted mt-1" style="font-size: 0.8rem;">
                                {{ number_format($progressSimpanan, 0) }}% dari Rp 50 Juta
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Pinjaman Aktif --}}
        <div class="col-md-3 mb-4">
            <a href="{{ route('admin.pinjaman.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-start-info h-100 hover-shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Pinjaman Aktif</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">Rp {{ number_format($totalPinjamanAktif, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cash-stack fs-2 text-gray-300"></i>
                            </div>
                        </div>
                        {{-- Progress Bar Pinjaman Aktif --}}
                        <div class="mt-3">
                            @php
                                $maxPinjaman = 100000000;
                                $progressPinjaman = ($totalPinjamanAktif / $maxPinjaman) * 100;
                            @endphp
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $progressPinjaman }}%" aria-valuenow="{{ $totalPinjamanAktif }}" aria-valuemin="0" aria-valuemax="{{ $maxPinjaman }}"></div>
                            </div>
                            <div class="text-end text-muted mt-1" style="font-size: 0.8rem;">
                                {{ number_format($progressPinjaman, 0) }}% dari Rp 100 Juta
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Pengajuan Menunggu --}}
        <div class="col-md-3 mb-4">
            <a href="{{ route('admin.pinjaman.pengajuan') }}" class="text-decoration-none">
                <div class="card shadow-sm border-start-warning h-100 hover-shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Pengajuan Menunggu</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $pengajuanMenunggu }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-hourglass-split fs-2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-start-primary { border-left: .25rem solid #0d6efd !important; }
    .border-start-success { border-left: .25rem solid #198754 !important; }
    .border-start-info { border-left: .25rem solid #0dcaf0 !important; }
    .border-start-warning { border-left: .25rem solid #ffc107 !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .hover-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important; transition: 0.3s; }
</style>
@endpush