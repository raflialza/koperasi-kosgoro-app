@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Manajemen Pinjaman Anggota</h3>
        <a href="{{ route('admin.laporan.pinjaman') }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <!-- Navigasi Tab -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'aktif' ? 'active' : '' }}" href="{{ route('admin.pinjaman.semua', ['status' => 'aktif']) }}">
                        <i class="bi bi-play-circle-fill me-2"></i>Aktif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'lunas' ? 'active' : '' }}" href="{{ route('admin.pinjaman.semua', ['status' => 'lunas']) }}">
                        <i class="bi bi-check-circle-fill me-2"></i>Lunas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'menunggu' ? 'active' : '' }}" href="{{ route('admin.pinjaman.semua', ['status' => 'menunggu']) }}">
                        <i class="bi bi-hourglass-split me-2"></i>Menunggu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'ditolak' ? 'active' : '' }}" href="{{ route('admin.pinjaman.semua', ['status' => 'ditolak']) }}">
                        <i class="bi bi-x-circle-fill me-2"></i>Ditolak
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.pinjaman.bayar-massal') }}" method="POST" id="form-bayar-massal">
                @csrf
                <!-- Header Aksi & Pencarian -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control bg-light border-0" placeholder="Ketik nama atau ID anggota...">
                    </div>
                    @if($status == 'aktif')
                    <button type="submit" class="btn btn-success" id="tombol-bayar-massal" disabled>
                        <i class="bi bi-check2-circle me-2"></i>Bayar Angsuran Terpilih
                    </button>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <!-- Daftar Pinjaman dengan Scroll -->
                <div class="list-group" id="pinjamanList" style="max-height: 600px; overflow-y: auto;">
                    {{-- Memuat daftar awal saat halaman dibuka --}}
                    @include('admin.pinjaman.partials.list-semua-pinjaman', ['semuaPinjaman' => $semuaPinjaman, 'status' => $status])
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const pinjamanList = document.getElementById('pinjamanList');
    const searchUrl = "{{ route('admin.pinjaman.semua.search') }}";
    const currentStatus = "{{ $status }}";

    let searchTimeout;

    searchInput.addEventListener('keyup', function () {
        const query = searchInput.value;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            pinjamanList.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            fetch(`${searchUrl}?query=${query}&status=${currentStatus}`)
                .then(response => response.text())
                .then(html => {
                    pinjamanList.innerHTML = html;
                    // Setelah daftar baru dimuat, inisialisasi ulang event listener untuk checkbox
                    initializeCheckboxes();
                });
        }, 300);
    });

    // Fungsi untuk inisialisasi checkbox
    function initializeCheckboxes() {
        const pilihSemuaCheckbox = document.getElementById('pilih-semua');
        const pilihPinjamanCheckboxes = document.querySelectorAll('.pilih-pinjaman');
        const tombolBayarMassal = document.getElementById('tombol-bayar-massal');

        function toggleTombolBayar() {
            if (!tombolBayarMassal) return;
            const adaYangDipilih = Array.from(pilihPinjamanCheckboxes).some(cb => cb.checked);
            tombolBayarMassal.disabled = !adaYangDipilih;
        }

        if (pilihSemuaCheckbox) {
            pilihSemuaCheckbox.addEventListener('change', function () {
                pilihPinjamanCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleTombolBayar();
            });
        }
        
        pilihPinjamanCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleTombolBayar);
        });

        toggleTombolBayar(); // Panggil saat inisialisasi
    }

    // Panggil fungsi inisialisasi saat halaman pertama kali dimuat
    initializeCheckboxes();
});
</script>
@endsection
