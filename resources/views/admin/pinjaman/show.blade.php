@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Kolom Kiri: Detail Pinjaman -->
        <div class="col-lg-8">
            <div class="card shadow-sm modern-card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pinjaman #{{ $pinjaman->id }}</h5>
                    <span class="badge rounded-pill 
                        @if($pinjaman->status == 'Disetujui') bg-success 
                        @elseif($pinjaman->status == 'Menunggu Persetujuan') bg-warning text-dark
                        @elseif($pinjaman->status == 'Lunas') bg-info text-dark
                        @else bg-danger 
                        @endif">
                        {{ $pinjaman->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Anggota:</strong> {{ $pinjaman->user->id_anggota }}</p>
                            <p><strong>Nama Anggota:</strong> {{ $pinjaman->user->nama }}</p>
                            <p><strong>Tanggal Pengajuan:</strong> {{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d M Y') }}</p>
                            @if($pinjaman->tanggal_disetujui)
                                <p><strong>Tanggal Disetujui:</strong> {{ \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->format('d M Y') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pokok Pinjaman:</strong> Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</p>
                            <p><strong>Margin:</strong> {{ $pinjaman->margin }}%</p>
                            <p><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
                            <p><strong>Angsuran/bulan:</strong> Rp {{ number_format($angsuranPerBulan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Keterangan:</strong> {{ $pinjaman->keterangan }}</p>
                    
                    <!-- Progress Bar Pembayaran -->
                    <h6 class="mt-4">Progres Pembayaran</h6>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ round($persentaseTerbayar) }}%;" 
                             aria-valuenow="{{ round($persentaseTerbayar) }}" 
                             aria-valuemin="0" aria-valuemax="100">
                             {{ round($persentaseTerbayar) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small><strong>Terbayar:</strong> Rp {{ number_format($pinjaman->angsuran->sum('jumlah_bayar'), 0, ',', '.') }}</small>
                        <small><strong>Sisa:</strong> Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</small>
                    </div>
                    <p class="text-center mt-2">
                        <strong>Total Tagihan: Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong>
                    </p>
                </div>
                <div class="card-footer bg-white text-end">
                     <a href="{{ route('admin.pinjaman.invoice', $pinjaman->id) }}" class="btn btn-outline-dark" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Invoice
                    </a>
                    @if($pinjaman->status == 'Menunggu Persetujuan')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-status="Disetujui">
                            <i class="bi bi-check-circle"></i> Setujui
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#updateStatusModal" data-status="Ditolak">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Tambah Angsuran -->
        <div class="col-lg-4">
            @if($pinjaman->status == 'Disetujui')
            <div class="card shadow-sm modern-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Input Angsuran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pinjaman.storeAngsuran', $pinjaman->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                            <input type="number" class="form-control" name="jumlah_bayar" id="jumlah_bayar" value="{{ round($angsuranPerBulan) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                            <input type="date" class="form-control" name="tanggal_bayar" id="tanggal_bayar" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Angsuran</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Riwayat Angsuran -->
    <div class="card shadow-sm modern-card mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Riwayat Angsuran</h5>
        </div>
        <div class="card-body">
             <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>Angsuran Ke</th>
                            <th>Tanggal Bayar</th>
                            <th>Jumlah Bayar</th>
                            <th>Diproses Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pinjaman->angsuran->sortBy('angsuran_ke') as $angsuran)
                        <tr>
                            <td>{{ $angsuran->angsuran_ke }}</td>
                            <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
                            <td>{{ $angsuran->processor->nama ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada angsuran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateStatusModalLabel">Konfirmasi Status Pinjaman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateStatusForm" action="{{ route('admin.pinjaman.updateStatus', $pinjaman->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <p>Anda yakin ingin mengubah status pinjaman ini menjadi <strong id="status-text"></strong>?</p>
            <input type="hidden" name="status" id="status-input">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Ya, Lanjutkan</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var updateStatusModal = document.getElementById('updateStatusModal');
    updateStatusModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var status = button.getAttribute('data-status');
        
        var modalTitle = updateStatusModal.querySelector('.modal-title');
        var statusText = updateStatusModal.querySelector('#status-text');
        var statusInput = updateStatusModal.querySelector('#status-input');
        
        modalTitle.textContent = 'Konfirmasi Status: ' + status;
        statusText.textContent = status;
        statusInput.value = status;
    });
});
</script>
@endpush
