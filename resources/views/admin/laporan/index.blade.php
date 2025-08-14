@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Cetak Laporan Koperasi</h4>

    <div class="row">
        <!-- Card Laporan Simpanan -->
        <div class="col-md-6">
            <div class="card shadow-sm modern-card">
                <div class="card-body">
                    <h5 class="card-title">Laporan Simpanan</h5>
                    <p class="card-text">Pilih rentang tanggal untuk mencetak rekapitulasi transaksi simpanan.</p>
                    <form action="{{ route('admin.laporan.simpanan.pdf') }}" method="GET" target="_blank">
                        <div class="row">
                            <div class="col">
                                <label for="start_date_simpanan" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date_simpanan" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="end_date_simpanan" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date_simpanan" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100">
                            Cetak Laporan Periode
                        </button>
                    </form>
                    <hr>
                    <a href="{{ route('admin.laporan.simpanan.pdf.keseluruhan') }}" target="_blank" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-printer-fill me-2"></i>Cetak Laporan Keseluruhan
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Laporan Pinjaman -->
        <div class="col-md-6">
            <div class="card shadow-sm modern-card">
                <div class="card-body">
                    <h5 class="card-title">Laporan Pinjaman</h5>
                    <p class="card-text">Pilih rentang tanggal untuk mencetak rekapitulasi pengajuan pinjaman.</p>
                    <form action="{{ route('admin.laporan.pinjaman.pdf') }}" method="GET" target="_blank">
                        <div class="row">
                            <div class="col">
                                <label for="start_date_pinjaman" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date_pinjaman" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="end_date_pinjaman" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date_pinjaman" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 w-100">
                            Cetak Laporan Periode
                        </button>
                    </form>
                    <hr>
                    <a href="{{ route('admin.laporan.pinjaman.pdf.keseluruhan') }}" target="_blank" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-printer-fill me-2"></i>Cetak Laporan Keseluruhan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
