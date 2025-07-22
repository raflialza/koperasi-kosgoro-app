@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Pinjaman Anda</h5>
                </div>
                <div class="card-body">
                    <strong>ID Anggota:</strong> {{ $pinjaman->user->id_anggota }} <br>
                    <strong>Nama:</strong> {{ $pinjaman->user->nama }} <br>
                    <strong>Total Pinjaman:</strong> Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }} <br>
                    <strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan <br>
                    <strong>Angsuran / Bulan:</strong> Rp {{ number_format($angsuranPerBulan, 0, ',', '.') }} <br>
                    <hr>
                    <strong class="text-danger">Sisa Pinjaman: Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</strong>
                </div>
            </div>
             <a href="{{ route('anggota.pinjaman.riwayat') }}" class="btn btn-secondary mt-3">Kembali ke Riwayat</a>
        </div>

        <div class="col-md-7">
             <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Riwayat Angsuran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Angsuran Ke-</th>
                                <th>Tanggal Bayar</th>
                                <th class="text-end">Jumlah Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjaman->angsuran as $angsuran)
                                <tr>
                                    <td>{{ $angsuran->angsuran_ke }}</td>
                                    <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') }}</td>
                                    <td class="text-end">Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada pembayaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection