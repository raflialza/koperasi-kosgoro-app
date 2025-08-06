<!-- Header "Pilih Semua" hanya jika ada data dan statusnya aktif -->
@if($status == 'aktif' && $semuaPinjaman->isNotEmpty())
    <div class="list-group-item d-flex align-items-center bg-light py-2">
        <input class="form-check-input me-3" type="checkbox" id="pilih-semua">
        <label class="form-check-label" for="pilih-semua">
            Pilih Semua
        </label>
    </div>
@endif

@forelse ($semuaPinjaman as $pinjaman)
    <div class="list-group-item list-group-item-action d-flex align-items-center py-3 listItem">
        @if($status == 'aktif')
            <input class="form-check-input me-3 pilih-pinjaman" type="checkbox" name="pinjaman_ids[]" value="{{ $pinjaman->id }}">
        @endif
        <div class="flex-grow-1">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1 fw-bold nama-anggota">{{ $pinjaman->user->nama }}</h6>
                <small class="text-muted">{{ $pinjaman->tanggal_pengajuan->diffForHumans() }}</small>
            </div>
            <p class="mb-1 id-anggota">
                {{ $pinjaman->user->id_anggota }} - Pinjaman Pokok: <strong>Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</strong>
            </p>
        </div>
        @if($pinjaman->status == 'disetujui' || $pinjaman->status == 'berjalan' || $pinjaman->status == 'lunas')
            <a href="{{ route('admin.pinjaman.bayar', $pinjaman->id) }}" class="btn btn-outline-primary ms-3" title="Lihat Detail">
                Detail
            </a>
        @else
            <span class="ms-3 text-muted">-</span>
        @endif
    </div>
@empty
    <div class="text-center py-5">
        <i class="bi bi-folder2-open fs-1 text-muted"></i>
        <p class="mt-3 text-muted">Tidak ada data pinjaman yang cocok dengan pencarian Anda.</p>
    </div>
@endforelse
