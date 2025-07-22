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
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-start-primary h-100">
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
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-start-success h-100">
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
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-start-info h-100">
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
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-start-warning h-100">
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
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Tambahkan sedikit CSS untuk garis di sisi kiri card --}}
<style>
    .border-start-primary { border-left: .25rem solid #0d6efd !important; }
    .border-start-success { border-left: .25rem solid #198754 !important; }
    .border-start-info { border-left: .25rem solid #0dcaf0 !important; }
    .border-start-warning { border-left: .25rem solid #ffc107 !important; }
    .text-gray-300 { color: #dddfeb !important; }
</style>
@endpush