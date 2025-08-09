@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Kolom Kiri: Detail Pinjaman & Form Pembayaran -->
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pinjaman</h5>
                    <a href="{{ url()->previous() }}" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Informasi Anggota -->
                    <h6>Informasi Anggota</h6>
                    <p class="mb-1"><strong>Nama:</strong> {{ $pinjaman->user->nama }}</p>
                    <p><strong>ID Anggota:</strong> {{ $pinjaman->user->id_anggota }}</p>
                    <hr>

                    <!-- Detail Pinjaman -->
                    <h6>Rincian Pinjaman</h6>
                    <p class="mb-1"><strong>Pinjaman Pokok:</strong> Rp{{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Bunga:</strong> {{ $pinjaman->persentase_bunga * 100 }}% (Rp{{ number_format($pinjaman->total_tagihan - $pinjaman->jumlah_pinjaman, 0, ',', '.') }})</p>
                    <div class="row mt-2">
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
                    <p><strong>Tgl Disetujui:</strong> {{ $pinjaman->tanggal_disetujui ? \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->format('d M Y') : '-' }}</p>
                    
                    <!-- === PERUBAHAN LOGIKA DI SINI === -->
                    
                    <!-- Form Pembayaran (Hanya untuk status 'disetujui') -->
                    @if ($pinjaman->status == 'disetujui')
                        <hr>
                        <h6>Form Pembayaran Angsuran ke-{{ $angsuranKe }}</h6>
                        <form action="{{ route('admin.pinjaman.storeAngsuran', $pinjaman->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                                <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" value="{{ $jumlahBayarDefault }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                                <input type="date" class="form-control" id="tanggal_bayar" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Pembayaran</button>
                        </form>
                    @elseif ($pinjaman->status == 'lunas')
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

        <!-- Kolom Kanan: Riwayat Angsuran (Hanya untuk pinjaman yang disetujui/lunas) -->
        @if($pinjaman->status == 'disetujui' || $pinjaman->status == 'lunas')
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Angsuran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Angsuran Ke-</th>
                                    <th>Tgl Bayar</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjaman->angsuran as $angsuran)
                                    <tr>
                                        <td>{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') }}</td>
                                        <td>Rp{{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
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
        @endif
    </div>
</div>
@endsection
