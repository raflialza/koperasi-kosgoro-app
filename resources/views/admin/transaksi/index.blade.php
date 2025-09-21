@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Semua Transaksi Koperasi</h4>

    <!-- Filter Card -->
    <div class="card shadow-sm modern-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.transaksi.semua') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select class="form-select" id="jenis" name="jenis">
                        <option value="">Semua</option>
                        <option value="simpanan" {{ ($jenis ?? '') == 'simpanan' ? 'selected' : '' }}>Simpanan</option>
                        <option value="angsuran" {{ ($jenis ?? '') == 'angsuran' ? 'selected' : '' }}>Angsuran</option>
                        <option value="pinjaman" {{ ($jenis ?? '') == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel-fill me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <div class="card shadow-sm modern-card">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Riwayat Transaksi</h5>
                </div>
                <div class="col-md-4">
                     <form action="{{ route('admin.transaksi.semua') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari Nama/ID Anggota..." value="{{ $search ?? '' }}">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Keterangan</th>
                            <th class="text-center">Jenis</th>
                            {{-- PERUBAHAN: Menggabungkan dua kolom menjadi satu --}}
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') }}</td>
                            <td>{{ $item->id_anggota }}</td>
                            <td>{{ $item->anggota }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td class="text-center">
                                @if($item->jenis_transaksi == 'Simpanan')
                                    <span class="badge bg-success">{{ $item->jenis_transaksi }}</span>
                                @elseif($item->jenis_transaksi == 'Angsuran')
                                     <span class="badge bg-info">{{ $item->jenis_transaksi }}</span>
                                @elseif($item->jenis_transaksi == 'Pinjaman')
                                     <span class="badge bg-danger">{{ $item->jenis_transaksi }}</span>
                                @endif
                            </td>
                            {{-- PERUBAHAN: Menampilkan jumlah dengan warna berbeda --}}
                            <td class="text-end fw-bold">
                                @if($item->pemasukan > 0)
                                    <span class="text-success">+ Rp {{ number_format($item->pemasukan, 0, ',', '.') }}</span>
                                @elseif($item->pengeluaran > 0)
                                    <span class="text-danger">- Rp {{ number_format($item->pengeluaran, 0, ',', '.') }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- PERUBAHAN: Colspan disesuaikan --}}
                            <td colspan="6" class="text-center py-4">
                                <p class="mb-1">Tidak ada data transaksi yang ditemukan.</p>
                                <small class="text-muted">Coba ubah filter atau kata kunci pencarian Anda.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-3">
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

