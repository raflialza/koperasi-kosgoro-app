@forelse ($daftarPengajuan as $pengajuan)
    <div class="list-group-item list-group-item-action d-flex flex-wrap align-items-center py-3">
        <div class="flex-grow-1 mb-2 mb-md-0">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1 fw-bold">{{ $pengajuan->user->nama }}</h6>
                <small class="text-muted">{{ $pengajuan->tanggal_pengajuan->diffForHumans() }}</small>
            </div>
            <p class="mb-1">
                {{ $pengajuan->user->id_anggota }} - Mengajukan <strong>Rp {{ number_format($pengajuan->jumlah_pinjaman, 0, ',', '.') }}</strong>
            </p>
            <small class="text-muted">Tenor: {{ $pengajuan->tenor }} bulan | Keperluan: {{ $pengajuan->keperluan }}</small>
        </div>
        
        <div class="ms-md-3 d-flex gap-2">
            <form action="{{ route('admin.pinjaman.proses', $pengajuan->id) }}" method="POST">
                @csrf
                <button type="submit" name="status" value="disetujui" class="btn btn-sm btn-outline-success" title="Setujui"><i class="bi bi-check-lg"></i></button>
            </form>
            <form action="{{ route('admin.pinjaman.proses', $pengajuan->id) }}" method="POST">
                @csrf
                <button type="submit" name="status" value="ditolak" class="btn btn-sm btn-outline-danger" title="Tolak"><i class="bi bi-x-lg"></i></button>
            </form>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <p class="mt-3 text-muted">Tidak ada pengajuan yang cocok dengan pencarian Anda.</p>
    </div>
@endforelse