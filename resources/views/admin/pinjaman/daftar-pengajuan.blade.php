@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Pengajuan Pinjaman Masuk</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Tgl Pengajuan</th>
                            <th class="text-end">Jumlah</th>
                            <th>Tenor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarPengajuan as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->user->id_anggota }}</td>
                                <td>{{ $pengajuan->user->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td class="text-end">{{ number_format($pengajuan->jumlah_pinjaman, 0, ',', '.') }}</td>
                                <td>{{ $pengajuan->tenor }} bulan</td>
                                <td>
                                    <form action="{{ route('admin.pinjaman.proses', $pengajuan->id) }}" method="POST">
                                        @csrf
                                        <div class="btn-group">
                                            <button type="submit" name="status" value="disetujui" class="btn btn-sm btn-success">Setujui</button>
                                            <button type="submit" name="status" value="ditolak" class="btn btn-sm btn-danger">Tolak</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada pengajuan pinjaman baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection