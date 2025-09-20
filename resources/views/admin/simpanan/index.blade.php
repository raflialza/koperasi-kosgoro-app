@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 me-3">Kelola Simpanan Anggota</h4>
        <div class="d-flex align-items-center flex-grow-1">
            <div class="flex-grow-1 me-2">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari anggota..." value="{{ $search ?? '' }}">
            </div>
            <a href="{{ route('admin.simpanan.create') }}" class="btn btn-primary text-nowrap">
                <i class="bi bi-plus-circle"></i> Tambah Simpanan
            </a>
        </div>
    </div>

    <div class="card shadow-sm modern-card">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table modern-table">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama</th>
                            <th class="text-end">Total Simpanan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="anggota-simpanan-list">
                        @include('admin.simpanan.partials.list-anggota-simpanan', ['semuaAnggota' => $semuaAnggota])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail Simpanan -->
<div class="modal fade" id="detailSimpananModal" tabindex="-1" aria-labelledby="detailSimpananModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailSimpananModalLabel">Rincian Simpanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nama:</strong> <span id="modal-nama"></span></p>
        <p class="mb-3"><strong>ID Anggota:</strong> <span id="modal-id-anggota"></span></p>
        <table class="table">
            <tr>
                <td>Simpanan Pokok</td>
                <td class="text-end fw-bold"><span id="modal-total-pokok"></span></td>
            </tr>
            <tr>
                <td>Simpanan Wajib</td>
                <td class="text-end fw-bold"><span id="modal-total-wajib"></span></td>
            </tr>
            <tr>
                <td>Simpanan Sukarela</td>
                <td class="text-end fw-bold"><span id="modal-total-sukarela"></span></td>
            </tr>
            <tr class="table-light">
                <td class="fw-bold">TOTAL KESELURUHAN</td>
                <td class="text-end fw-bold"><span id="modal-total-semua"></span></td>
            </tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- PERUBAHAN: JavaScript untuk membaca data langsung dari atribut tombol ---
    const detailModal = document.getElementById('detailSimpananModal');
    if (detailModal) {
        detailModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const modalBody = detailModal.querySelector('.modal-body');
            
            // Ambil data langsung dari atribut data-* tombol
            modalBody.querySelector('#modal-nama').textContent = button.getAttribute('data-nama');
            modalBody.querySelector('#modal-id-anggota').textContent = button.getAttribute('data-id-anggota');
            modalBody.querySelector('#modal-total-pokok').textContent = button.getAttribute('data-total-pokok');
            modalBody.querySelector('#modal-total-wajib').textContent = button.getAttribute('data-total-wajib');
            modalBody.querySelector('#modal-total-sukarela').textContent = button.getAttribute('data-total-sukarela');
            modalBody.querySelector('#modal-total-semua').textContent = button.getAttribute('data-total-semua');
        });
    }

    // --- Pencarian Otomatis saat Mengetik (tidak ada perubahan) ---
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const listBody = document.getElementById('anggota-simpanan-list');

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const query = this.value;

        searchTimeout = setTimeout(function() {
            const url = `{{ route('admin.simpanan.index') }}?search=${query}`;
            
            listBody.innerHTML = '<tr><td colspan="4" class="text-center">Memuat...</td></tr>';

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                listBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                listBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>';
            });
        }, 300); // Jeda 300ms
    });
});
</script>
@endpush
