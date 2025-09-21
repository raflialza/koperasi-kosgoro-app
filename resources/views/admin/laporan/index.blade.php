@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Cetak Laporan</h4>

    <div class="row">
        <!-- Laporan Simpanan -->
        <div class="col-md-6">
            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-wallet-fill text-success me-2"></i>Laporan Simpanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.laporan.simpanan.pdf') }}" method="GET" target="_blank" id="form-simpanan">
                        <div class="mb-3">
                            <label for="simpanan_start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="simpanan_start_date" name="start_date">
                        </div>
                        <div class="mb-3">
                            <label for="simpanan_end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="simpanan_end_date" name="end_date">
                        </div>
                        <p class="text-muted small">Kosongkan tanggal untuk mencetak semua riwayat simpanan.</p>
                        <div class="d-flex gap-2">
                             <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak PDF
                            </button>
                            {{-- PERBAIKAN: Tombol Excel sekarang memanggil fungsi yang diperbarui --}}
                            <button type="button" 
                                    onclick="submitFormAsExcel('form-simpanan', '{{ route('admin.laporan.simpanan.excel') }}', '{{ route('admin.laporan.simpanan.pdf') }}')" 
                                    class="btn btn-success w-100">
                                <i class="bi bi-file-earmark-excel-fill me-2"></i>Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Laporan Pinjaman -->
        <div class="col-md-6">
            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="bi bi-cash-stack text-info me-2"></i>Laporan Pinjaman</h5>
                </div>
                <div class="card-body">
                     <form action="{{ route('admin.laporan.pinjaman.pdf') }}" method="GET" target="_blank" id="form-pinjaman">
                        <div class="mb-3">
                            <label for="pinjaman_start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="pinjaman_start_date" name="start_date">
                        </div>
                        <div class="mb-3">
                            <label for="pinjaman_end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="pinjaman_end_date" name="end_date">
                        </div>
                         <p class="text-muted small">Kosongkan tanggal untuk mencetak semua riwayat pinjaman.</p>
                         <div class="d-flex gap-2">
                             <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak PDF
                            </button>
                             {{-- PERBAIKAN: Tombol Excel sekarang memanggil fungsi yang diperbarui --}}
                             <button type="button" 
                                     onclick="submitFormAsExcel('form-pinjaman', '{{ route('admin.laporan.pinjaman.excel') }}', '{{ route('admin.laporan.pinjaman.pdf') }}')" 
                                     class="btn btn-success w-100">
                                <i class="bi bi-file-earmark-excel-fill me-2"></i>Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * --- FUNGSI JAVASCRIPT DIPERBARUI ---
     * Fungsi ini sekarang menerima rute PDF asli sebagai parameter ketiga.
     * Setelah mengirimkan form ke rute Excel, ia akan langsung mengembalikan
     * action form ke rute PDF, sehingga tombol "Cetak PDF" akan berfungsi
     * dengan benar pada klik berikutnya.
     */
    function submitFormAsExcel(formId, excelRoute, pdfRoute) {
        const form = document.getElementById(formId);
        
        // Simpan action asli untuk dikembalikan nanti
        const originalAction = form.action;
        const originalTarget = form.target;

        // Ubah action ke rute Excel dan hapus target
        form.action = excelRoute;
        form.removeAttribute('target');
        form.submit();

        // Segera kembalikan action dan target ke keadaan semula
        form.action = pdfRoute;
        if (originalTarget) {
            form.target = originalTarget;
        }
    }
</script>
@endpush

