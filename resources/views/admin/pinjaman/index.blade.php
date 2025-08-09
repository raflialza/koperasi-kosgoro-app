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
                {{-- Notifikasi akan ditangani oleh SweetAlert --}}
            @endif

            <!-- Form untuk pembayaran massal -->
            <form action="{{ route('admin.pinjaman.bayar-massal') }}" method="POST" id="form-bayar-massal">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Ketik nama atau ID anggota..." value="{{ $search ?? '' }}" style="max-width: 400px;">
                    
                    <!-- Tombol Bayar Massal (hanya muncul di tab Aktif) -->
                    @if($status == 'disetujui')
                    <button type="submit" class="btn btn-success" id="tombol-bayar-massal" disabled>
                        <i class="bi bi-check2-circle me-2"></i>Bayar Angsuran Terpilih
                    </button>
                    @endif
                </div>

                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-hover">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <!-- Kolom Checklist (hanya muncul di tab Aktif) -->
                                @if($status == 'disetujui')
                                <th style="width: 1%;"><input class="form-check-input" type="checkbox" id="pilih-semua"></th>
                                @endif
                                <th>Anggota</th>
                                <th>Tgl Disetujui</th>
                                <th>Total Tagihan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pinjaman-list-body">
                            @include('admin.pinjaman.partials.list-semua-pinjaman', ['semuaPinjaman' => $semuaPinjaman, 'status' => $status])
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Notifikasi Sukses ---
    const successMessage = @json(session('success'));
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

    // --- Variabel Global ---
    const listBody = document.getElementById('pinjaman-list-body');
    const searchInput = document.getElementById('searchInput');
    const currentStatus = "{{ $status }}";
    let searchTimeout;

    // --- Fungsi untuk Pencarian Otomatis ---
    function fetchPinjaman() {
        const query = searchInput.value;
        listBody.innerHTML = '<tr><td colspan="6" class="text-center">Memuat...</td></tr>';
        
        fetch(`{{ route('admin.pinjaman.index') }}?status=${currentStatus}&search=${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            listBody.innerHTML = html;
            // Inisialisasi ulang event listener untuk checkbox setelah konten baru dimuat
            initializeCheckboxes(); 
        })
        .catch(error => {
            console.error('Error:', error);
            listBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>';
        });
    }

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchPinjaman, 300);
    });

    // --- Fungsi untuk Checklist dan Pembayaran Massal ---
    function initializeCheckboxes() {
        const tombolBayarMassal = document.getElementById('tombol-bayar-massal');
        const pilihSemuaCheckbox = document.getElementById('pilih-semua');
        const formBayarMassal = document.getElementById('form-bayar-massal');

        function toggleBayarButton() {
            if (!tombolBayarMassal) return;
            const terpilih = listBody.querySelectorAll('.pilih-pinjaman:checked').length > 0;
            tombolBayarMassal.disabled = !terpilih;
        }

        if (pilihSemuaCheckbox) {
            pilihSemuaCheckbox.addEventListener('change', function() {
                const checkboxes = listBody.querySelectorAll('.pilih-pinjaman');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                toggleBayarButton();
            });
        }

        listBody.addEventListener('change', function(event) {
            if (event.target.classList.contains('pilih-pinjaman')) {
                toggleBayarButton();
            }
        });

        if (formBayarMassal) {
            formBayarMassal.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Pembayaran',
                    text: "Anda akan membayar satu angsuran untuk semua pinjaman yang dipilih. Lanjutkan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Bayar!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        }
    }

    // Panggil fungsi inisialisasi saat halaman pertama kali dimuat
    initializeCheckboxes();
});
</script>
@endpush
