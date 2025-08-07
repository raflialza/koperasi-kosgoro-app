@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Proses Pengajuan Pinjaman</h4>
    </div>

    <div class="card shadow-sm">
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
                <table class="table table-hover">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Anggota</th>
                            <th>Tgl Pengajuan</th>
                            <th>Jumlah</th>
                            <th>Tenor</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pengajuan-list-body">
                        {{-- Memuat daftar awal saat halaman dibuka --}}
                        @include('admin.pinjaman.partials.list-pengajuan', ['daftarPengajuan' => $daftarPengajuan])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail Pinjaman (tidak berubah) -->
<div class="modal fade" id="detailPinjamanModal" tabindex="-1" aria-labelledby="detailPinjamanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailPinjamanModalLabel">Detail Pengajuan Pinjaman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nama:</strong> <span id="modal-nama"></span></p>
        <p><strong>ID Anggota:</strong> <span id="modal-id-anggota"></span></p>
        <p><strong>Tanggal Pengajuan:</strong> <span id="modal-tanggal"></span></p>
        <p><strong>Jumlah Pinjaman:</strong> <span id="modal-jumlah"></span></p>
        <p><strong>Tenor:</strong> <span id="modal-tenor"></span></p>
        <hr>
        <p><strong>Keperluan:</strong></p>
        <p id="modal-keperluan" style="white-space: pre-wrap;"></p>
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
// JavaScript untuk Modal (tidak berubah)
const detailPinjamanModal = document.getElementById('detailPinjamanModal');
if (detailPinjamanModal) {
    detailPinjamanModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const nama = button.getAttribute('data-nama');
        const idAnggota = button.getAttribute('data-id-anggota');
        const tanggal = button.getAttribute('data-tanggal');
        const jumlah = button.getAttribute('data-jumlah');
        const tenor = button.getAttribute('data-tenor');
        const keperluan = button.getAttribute('data-keperluan');
        
        detailPinjamanModal.querySelector('#modal-nama').textContent = nama;
        detailPinjamanModal.querySelector('#modal-id-anggota').textContent = idAnggota;
        detailPinjamanModal.querySelector('#modal-tanggal').textContent = tanggal;
        detailPinjamanModal.querySelector('#modal-jumlah').textContent = jumlah;
        detailPinjamanModal.querySelector('#modal-tenor').textContent = tenor;
        detailPinjamanModal.querySelector('#modal-keperluan').textContent = keperluan;
    });
}

// JavaScript BARU untuk Pencarian Otomatis
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const listBody = document.getElementById('pengajuan-list-body');

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value;

        searchTimeout = setTimeout(function() {
            fetch(`{{ route('admin.pinjaman.pengajuan') }}?search=${query}`, {
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
                listBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>';
            });
        }, 300); // Jeda 300ms setelah mengetik
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const listBody = document.getElementById('pengajuan-list-body');

    // Gunakan event delegation karena konten bisa berubah karena AJAX
    listBody.addEventListener('click', function(event) {
        const button = event.target.closest('.action-btn');

        if (button) {
            event.preventDefault(); // Mencegah aksi default
            const form = button.closest('form');
            const action = button.getAttribute('data-action');

            Swal.fire({
                title: `Anda yakin ingin ${action} pengajuan ini?`,
                text: "Aksi ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Ya, ${action}!`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form jika dikonfirmasi
                }
            });
        }
    });
});
</script>
@endpush
