@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Proses Pengajuan Pinjaman</h4>
    </div>

    <div class="card shadow-sm modern-card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Input Pencarian (tanpa tag form) -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Ketik nama atau ID anggota untuk mencari..." value="{{ $search ?? '' }}">
            </div>

            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table modern-table table-hover">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Jumlah Pinjaman</th>
                            <th>Margin</th>
                            <th>Tenor</th>
                            <th>Tgl Pengajuan</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pengajuan-list-body">
                        {{-- PERBAIKAN DI SINI: Menggunakan variabel 'pinjaman' --}}
                        @include('admin.pinjaman.partials.list-pengajuan', ['pinjaman' => $pinjaman])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const listBody = document.getElementById('pengajuan-list-body');

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value;

        searchTimeout = setTimeout(function() {
            // PERBAIKAN DI SINI: Pastikan URL selalu menyertakan status
            const url = `{{ route('admin.pinjaman.index') }}?status=Menunggu Persetujuan&search=${query}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                listBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                listBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>';
            });
        }, 300); // Jeda 300ms setelah mengetik
    });
});
</script>
@endpush
