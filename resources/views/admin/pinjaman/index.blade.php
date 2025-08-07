@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Manajemen Pinjaman Anggota</h4>
        <a href="{{ route('admin.laporan.pinjaman.pdf') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="bi bi-printer-fill me-2"></i>Cetak Laporan
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 pt-3">
            <!-- Navigasi Tab untuk Status Pinjaman -->
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'disetujui' ? 'active' : '' }}" href="{{ route('admin.pinjaman.index', ['status' => 'disetujui']) }}">
                        Aktif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'lunas' ? 'active' : '' }}" href="{{ route('admin.pinjaman.index', ['status' => 'lunas']) }}">
                        Lunas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'ditolak' ? 'active' : '' }}" href="{{ route('admin.pinjaman.index', ['status' => 'ditolak']) }}">
                        Ditolak
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Input Pencarian -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Ketik nama atau ID anggota untuk mencari..." value="{{ $search ?? '' }}">
            </div>

            <!-- Div untuk membuat tabel bisa di-scroll -->
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Anggota</th>
                            <th>Tgl Disetujui</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pinjaman-list-body">
                        {{-- Memuat daftar awal saat halaman dibuka --}}
                        @include('admin.pinjaman.partials.list-semua-pinjaman', ['semuaPinjaman' => $semuaPinjaman])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const pinjamanListBody = document.getElementById('pinjaman-list-body');
    const currentStatus = "{{ $status }}";

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value;

        searchTimeout = setTimeout(function() {
            // Kirim request ke server
            fetch(`{{ route('admin.pinjaman.index') }}?status=${currentStatus}&search=${query}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Ganti isi dari <tbody> dengan hasil baru
                pinjamanListBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                pinjamanListBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan saat memuat data.</td></tr>';
            });
        }, 300); // Jeda 300ms
    });
});
</script>
@endsection
