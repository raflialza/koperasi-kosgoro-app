@forelse ($semuaPinjaman as $pinjaman)
    <tr>
        <!-- Kolom Checklist (hanya untuk pinjaman aktif) -->
        @if($status == 'disetujui')
        <td>
            <input class="form-check-input pilih-pinjaman" type="checkbox" name="pinjaman_ids[]" value="{{ $pinjaman->id }}">
        </td>
        @endif
        <td>
            <strong>{{ $pinjaman->user->nama }}</strong><br>
            <small class="text-muted">{{ $pinjaman->user->id_anggota }}</small>
        </td>
        <td>
            {{ $pinjaman->tanggal_disetujui ? \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->format('d M Y') : '-' }}
        </td>
        <td>Rp{{ number_format($pinjaman->total_tagihan, 0, ',', '.') }}</td>
        <td>
            @if($pinjaman->status == 'disetujui')
                <span class="badge bg-success">Aktif</span>
            @elseif($pinjaman->status == 'lunas')
                <span class="badge bg-info">Lunas</span>
            @elseif($pinjaman->status == 'ditolak')
                <span class="badge bg-danger">Ditolak</span>
            @endif
        </td>
        <td>
            <a href="{{ route('admin.pinjaman.show', $pinjaman->id) }}" class="btn btn-sm btn-outline-primary">
                Detail
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-4">Tidak ada data pinjaman untuk status ini.</td>
    </tr>
@endforelse
