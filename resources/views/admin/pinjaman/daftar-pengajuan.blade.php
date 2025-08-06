@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Proses Pengajuan Pinjaman</h3>
        <span class="badge bg-warning-subtle text-warning-emphasis fs-6">
            {{ $daftarPengajuan->count() }} Pengajuan Menunggu
        </span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control bg-light border-0" placeholder="Ketik nama atau ID anggota untuk mencari...">
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="list-group" id="pengajuanList">
                {{-- Memuat daftar awal saat halaman dibuka --}}
                @include('admin.pinjaman.partials.list-pengajuan', ['daftarPengajuan' => $daftarPengajuan])
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const pengajuanList = document.getElementById('pengajuanList');
    const searchUrl = "{{ route('admin.pinjaman.search') }}";

    let searchTimeout;

    searchInput.addEventListener('keyup', function () {
        const query = searchInput.value;
        
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            pengajuanList.innerHTML = '<p class="text-center text-muted py-5">Mencari...</p>';

            fetch(`${searchUrl}?query=${query}`)
                .then(response => response.text())
                .then(html => {
                    pengajuanList.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    pengajuanList.innerHTML = '<p class="text-center text-danger py-5">Terjadi kesalahan.</p>';
                });
        }, 300);
    });
});
</script>
@endsection