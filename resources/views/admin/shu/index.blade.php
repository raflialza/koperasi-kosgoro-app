@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Perhitungan Sisa Hasil Usaha (SHU)</h4>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm modern-card">
                <div class="card-body">
                    <h5 class="card-title">Input Data SHU</h5>
                    <p class="card-text">Masukkan tahun buku dan total SHU yang akan dibagikan kepada anggota berdasarkan proporsi simpanan (Pokok & Wajib).</p>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- PERUBAHAN ADA DI BARIS INI -->
                    <form action="{{ route('admin.laporan.shu.cetak') }}" method="GET" target="_blank">
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun Buku</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="{{ date('Y') - 1 }}" placeholder="Contoh: 2024" required>
                        </div>
                        <div class="mb-3">
                            <label for="total_shu" class="form-label">Total SHU yang Dibagikan (Rp)</label>
                            <input type="number" class="form-control" id="total_shu" name="total_shu" placeholder="Contoh: 10000000" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-calculator-fill me-2"></i>Hitung dan Cetak Laporan SHU
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
