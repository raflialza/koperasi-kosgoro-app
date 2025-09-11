@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 me-3">Anggota</h4>
        <div class="d-flex align-items-center flex-grow-1">
            <!-- Input Pencarian -->
            <div class="flex-grow-1 me-2">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari anggota..." value="{{ $search ?? '' }}">
            </div>
            <!-- Filter Instansi -->
            <select class="form-select me-2" id="instansiFilter" style="width: auto;">
                <option value="">Semua Instansi</option>
                <option value="SMP" @if(isset($instansi) && $instansi == 'SMP') selected @endif>SMP</option>
                <option value="SMA" @if(isset($instansi) && $instansi == 'SMA') selected @endif>SMA</option>
                <option value="SMK" @if(isset($instansi) && $instansi == 'SMK') selected @endif>SMK</option>
            </select>
            <!-- Tombol Tambah Anggota -->
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary text-nowrap">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>
    </div>

    <!-- Card untuk Tabel -->
    <div class="card shadow-sm modern-card">
        <div class="card-body">
            
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table modern-table">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Instansi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="anggota-list-body">
                        {{-- Memuat daftar anggota awal dari file partial --}}
                        @include('admin.anggota.partials.list-anggota', ['anggota' => $anggota])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail Anggota -->
<div class="modal fade" id="detailAnggotaModal" tabindex="-1" aria-labelledby="detailAnggotaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailAnggotaModalLabel">Detail Data Anggota</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Anggota:</strong> <span id="modal-id-anggota"></span></p>
        <p><strong>Nama Lengkap:</strong> <span id="modal-nama"></span></p>
        <p><strong>Email:</strong> <span id="modal-email"></span></p>
        <p><strong>No. Telepon:</strong> <span id="modal-no-telp"></span></p>
        <p><strong>Instansi:</strong> <span id="modal-instansi"></span></p>
        <p><strong>Tahun Gabung:</strong> <span id="modal-tahun-gabung"></span></p>
        <hr>
        <p><strong>Alamat:</strong></p>
        <p id="modal-alamat" style="white-space: pre-wrap;"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Notifikasi Sukses dengan SweetAlert ---
    const successMessage = @json(session('success'));
    const errorMessage = @json(session('error'));

    if (successMessage) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: successMessage,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... Gagal!',
            text: errorMessage, // Tampilkan pesan dari controller di sini
        });
    }

    // --- Pencarian dan Filter AJAX ---
let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const instansiFilter = document.getElementById('instansiFilter');
    const listBody = document.getElementById('anggota-list-body');

    function fetchAnggota() {
        const query = searchInput.value;
        const instansi = instansiFilter.value;
        
        // ==== PERBAIKAN DI SINI ====
        // Menggunakan URL::route() untuk memastikan URL yang dihasilkan
        // sesuai dengan protokol (http/https) yang sedang digunakan.
        const url = new URL("{{ route('admin.anggota.index') }}");
        url.searchParams.append('search', query);
        url.searchParams.append('instansi', instansi);
        // ===========================

        listBody.innerHTML = '<tr><td colspan="5" class="text-center">Memuat...</td></tr>';

        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => {
            if (!response.ok) {
                console.error('Network response was not ok:', response.statusText);
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => { listBody.innerHTML = html; })
        .catch(error => {
            console.error('Fetch error:', error);
            listBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data. Periksa koneksi dan coba lagi.</td></tr>';
        });
    }

    searchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchAnggota, 300);
    });
    instansiFilter.addEventListener('change', fetchAnggota);

    // --- Konfirmasi Aksi dengan SweetAlert ---
    listBody.addEventListener('click', function(event) {
        const deleteForm = event.target.closest('.form-delete');

        // Konfirmasi Hapus (Konfirmasi Edit dihapus dari sini)
        if (deleteForm) {
             event.preventDefault();
             Swal.fire({
                title: 'Anda Yakin?',
                text: "Data anggota yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        }
    });

    // --- Mengisi data ke Modal Detail ---
    const detailAnggotaModal = document.getElementById('detailAnggotaModal');
    if (detailAnggotaModal) {
        detailAnggotaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const nama = button.getAttribute('data-nama');
            const idAnggota = button.getAttribute('data-id-anggota');
            const email = button.getAttribute('data-email');
            const noTelp = button.getAttribute('data-no-telp');
            const alamat = button.getAttribute('data-alamat');
            const instansi = button.getAttribute('data-instansi');
            const tahunGabung = button.getAttribute('data-tahun-gabung');
            
            detailAnggotaModal.querySelector('#modal-nama').textContent = nama;
            detailAnggotaModal.querySelector('#modal-id-anggota').textContent = idAnggota;
            detailAnggotaModal.querySelector('#modal-email').textContent = email;
            detailAnggotaModal.querySelector('#modal-no-telp').textContent = noTelp;
            detailAnggotaModal.querySelector('#modal-alamat').textContent = alamat;
            detailAnggotaModal.querySelector('#modal-instansi').textContent = instansi;
            detailAnggotaModal.querySelector('#modal-tahun-gabung').textContent = tahunGabung;
        });
    }
});
</script>
@endpush
