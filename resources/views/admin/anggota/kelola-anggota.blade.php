@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Manajemen Data Anggota</h5>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota
            </a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Ketik nama, ID, atau email untuk mencari...">
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Anggota</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="anggotaList">
                    @include('admin.anggota.partials.list-anggota', ['anggota' => $anggota])
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const anggotaList = document.getElementById('anggotaList');
    const searchUrl = "{{ route('admin.anggota.search') }}";
    let searchTimeout;

    searchInput.addEventListener('keyup', function () {
        const query = searchInput.value;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            anggotaList.innerHTML = '<tr><td colspan="4" class="text-center">Mencari...</td></tr>';
            fetch(`${searchUrl}?query=${query}`)
                .then(response => response.text())
                .then(html => {
                    anggotaList.innerHTML = html;
                });
        }, 300);
    });
});
</script>
@endsection