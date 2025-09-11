
@forelse ($daftarPengajuan as $item)
    <tr>
        <td>
            <strong>{{ $item->user->nama ?? 'User tidak ditemukan' }}</strong><br>
            <small class="text-muted">{{ $item->user->id_anggota }}</small>
        </td>
        <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}</td>
        <td>Rp{{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}</td>
        <td>{{ $item->tenor }} bulan</td>
        <td>
            <div class="d-flex">
                <!-- Tombol Detail -->
                <button type="button" class="btn btn-sm btn-info me-1" 
                    data-bs-toggle="modal" 
                    data-bs-target="#detailPinjamanModal"
                    data-nama="{{ $item->user->nama }}"
                    data-id-anggota="{{ $item->user->id_anggota }}"
                    data-jumlah="Rp{{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}"
                    data-tenor="{{ $item->tenor }} bulan"
                    data-keperluan="{{ $item->keperluan }}"
                    data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d M Y') }}"
                    title="Lihat Detail">
                    <i class="bi bi-eye-fill"></i>
                </button>

                <!-- Tombol Setujui dengan SweetAlert -->
                <form action="{{ route('admin.pinjaman.updateStatus', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="disetujui">
                    <button type="button" class="btn btn-sm btn-success me-1 action-btn" data-action="menyetujui" title="Setujui">
                        <i class="bi bi-check-circle-fill"></i>
                    </button>
                </form>

                <!-- Tombol Tolak dengan SweetAlert -->
                <form action="{{ route('admin.pinjaman.updateStatus', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="ditolak">
                    <button type="button" class="btn btn-sm btn-danger action-btn" data-action="menolak" title="Tolak">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">Tidak ada pengajuan pinjaman yang cocok dengan pencarian Anda.</td>
    </tr>
@endforelse