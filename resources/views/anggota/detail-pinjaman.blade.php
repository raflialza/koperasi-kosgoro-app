@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Kolom Kiri: Detail Pinjaman -->
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4 modern-card">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pinjaman Anda</h5>
                    <!-- Tombol Cetak Invoice -->
                    <a href="{{ route('anggota.pinjaman.invoice', $pinjaman->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-printer-fill me-2"></i>Cetak Invoice
                    </a>
                </div>
                <div class="card-body">
                    <!-- Rincian Pinjaman -->
                    <h6>Rincian Pinjaman</h6>
                    <p class="mb-1"><strong>Pinjaman Pokok:</strong> Rp{{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Bunga:</strong> {{ $pinjaman->persentase_bunga * 100 }}% (Rp{{ number_format($pinjaman->total_tagihan - $pinjaman->jumlah_pinjaman, 0, ',', '.') }})</p>
                    <div class="row mt-3">
                        <div class="col-6">
                            <p class="mb-1"><strong>Total Tagihan:</strong></p>
                            <h5>Rp{{ number_format($pinjaman->total_tagihan, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Sisa Tagihan:</strong></p>
                            <h5 class="text-danger">Rp{{ number_format($sisaPinjaman, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <p class="mb-1 mt-2"><strong>Angsuran/Bulan:</strong> Rp{{ number_format($angsuranPerBulan, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
                    <p><strong>Tgl Disetujui:</strong> {{ \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->format('d M Y') }}</p>

                    <!-- Status Lunas/Ditolak -->
                    @if ($pinjaman->status == 'lunas')
                        <div class="alert alert-info text-center mt-4">
                            <i class="bi bi-check-circle-fill me-2"></i> Pinjaman ini sudah lunas.
                        </div>
                    @elseif ($pinjaman->status == 'ditolak')
                        <div class="alert alert-danger text-center mt-4">
                           <i class="bi bi-x-circle-fill me-2"></i> Pinjaman ini telah ditolak.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Angsuran -->
        <div class="col-lg-7">
            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Riwayat Angsuran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>Angsuran Ke-</th>
                                    <th>Tanggal Bayar</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjaman->angsuran as $angsuran)
                                    <tr>
                                        <td>{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') }}</td>
                                        <td class="text-end">Rp{{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada riwayat pembayaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
