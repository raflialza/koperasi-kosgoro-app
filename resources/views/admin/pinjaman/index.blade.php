@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Semua Pinjaman</h4>
    </div>

    <div class="card shadow-sm modern-card">
        <div class="card-header bg-white border-0">
            <!-- Navigasi Tab -->
            <ul class="nav nav-tabs card-header-tabs" id="pinjamanTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status == 'Disetujui' ? 'active' : '' }}" href="{{ route('admin.pinjaman.index', ['status' => 'Disetujui']) }}">Aktif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status == 'Lunas' ? 'active' : '' }}" href="{{ route('admin.pinjaman.index', ['status' => 'Lunas']) }}">Lunas</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status == 'Ditolak' ? 'active' : '' }}" href="{{ route('admin.pinjaman.index', ['status' => 'Ditolak']) }}">Ditolak</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <!-- Input Pencarian -->
                <div class="input-group" style="width: 50%;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari nama atau ID anggota..." value="{{ $search ?? '' }}">
                </div>
                <!-- Tombol Bayar Massal (hanya muncul di tab Aktif) -->
                @if($status == 'Disetujui')
                    <button type="submit" form="form-bayar-massal" class="btn btn-success" id="btn-bayar-massal" disabled>
                        <i class="bi bi-cash-stack"></i> Bayar Angsuran Terpilih
                    </button>
                @endif
            </div>

            <!-- Form untuk Bayar Massal -->
            <form action="{{ route('admin.pinjaman.bayar-massal') }}" method="POST" id="form-bayar-massal">
                @csrf
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table modern-table table-hover">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                @if($status == 'Disetujui')
                                    <th style="width: 5%;"><input type="checkbox" id="check-all"></th>
                                @endif
                                <th>ID Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Pokok Pinjaman</th>
                                <th>Total Tagihan</th>
                                <th>Terbayar</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
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
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const listBody = document.getElementById('pinjaman-list-body');
    const currentStatus = @json($status);

    function fetchPinjaman() {
        const query = searchInput.value;
        const url = `{{ route('admin.pinjaman.index') }}?status=${currentStatus}&search=${query}`;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.text())
        .then(html => {
            listBody.innerHTML = html;
            attachCheckboxListeners(); // Pasang ulang listener setelah memuat ulang
        })
        .catch(error => {
            console.error('Error:', error);
            listBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Gagal memuat data.</td></tr>';
        });
    }

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchPinjaman, 300);
    });

    // --- Logika untuk Checkbox Pembayaran Massal ---
    function attachCheckboxListeners() {
        const checkAll = document.getElementById('check-all');
        const itemChecks = document.querySelectorAll('.check-item');
        const bayarMassalBtn = document.getElementById('btn-bayar-massal');

        if (!checkAll) return; // Hanya jalankan jika ada checkbox

        function toggleButtonState() {
            const anyChecked = Array.from(itemChecks).some(c => c.checked);
            bayarMassalBtn.disabled = !anyChecked;
        }

        checkAll.addEventListener('change', function() {
            itemChecks.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleButtonState();
        });

        itemChecks.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    checkAll.checked = false;
                } else if (Array.from(itemChecks).every(c => c.checked)) {
                    checkAll.checked = true;
                }
                toggleButtonState();
            });
        });
        
        // --- Form Submit Confirmation ---
        const formBayarMassal = document.getElementById('form-bayar-massal');
        if(formBayarMassal) {
            formBayarMassal.addEventListener('submit', function(e) {
                e.preventDefault();
                const count = document.querySelectorAll('.check-item:checked').length;
                Swal.fire({
                    title: `Bayar ${count} Angsuran?`,
                    text: "Aksi ini akan mencatat satu kali angsuran untuk setiap pinjaman yang dipilih.",
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

    attachCheckboxListeners(); // Jalankan saat halaman pertama kali dimuat
});
</script>
@endpush
