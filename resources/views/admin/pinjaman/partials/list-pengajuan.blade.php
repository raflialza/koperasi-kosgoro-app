@forelse ($pinjaman as $p)
<tr>
    <td>{{ $p->user->id_anggota ?? 'N/A' }}</td>
    <td>{{ $p->user->nama ?? 'N/A' }}</td>
    <td>Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
    <td>{{ $p->margin }}%</td>
    <td>{{ $p->tenor }} bulan</td>
    <td>{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
    <td>
        <a href="{{ route('admin.pinjaman.show', $p->id) }}" class="btn btn-sm btn-info">
            <i class="bi bi-eye"></i> Detail
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">Tidak ada pengajuan pinjaman baru.</td>
</tr>
@endforelse
