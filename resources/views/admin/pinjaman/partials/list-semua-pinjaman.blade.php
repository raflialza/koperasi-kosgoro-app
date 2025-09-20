@php
    // Fungsi bantuan untuk menentukan warna badge berdasarkan status
    function getStatusBadgeClass($status) {
        switch ($status) {
            case 'Disetujui': return 'bg-primary';
            case 'Lunas': return 'bg-success';
            case 'Ditolak': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
@endphp

@forelse($semuaPinjaman as $pinjaman)
    @php
        // Lakukan perhitungan di sini agar view tetap bersih
        $pokok = $pinjaman->jumlah_pinjaman;
        $totalMargin = $pokok * ($pinjaman->margin / 100);
        $totalTagihan = $pokok + $totalMargin;
        $totalTerbayar = $pinjaman->angsuran->sum('jumlah_bayar');
        $persentaseTerbayar = $totalTagihan > 0 ? ($totalTerbayar / $totalTagihan) * 100 : 0;
    @endphp
    <tr>
        @if($status == 'Disetujui')
            <td><input type="checkbox" class="form-check-input check-item" name="pinjaman_ids[]" value="{{ $pinjaman->id }}"></td>
        @endif
        <td>{{ $pinjaman->user->id_anggota }}</td>
        <td>{{ $pinjaman->user->nama }}</td>
        <td>Rp {{ number_format($pokok, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
        <td>
            @if($status == 'Disetujui')
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $persentaseTerbayar }}%;" aria-valuenow="{{ $persentaseTerbayar }}" aria-valuemin="0" aria-valuemax="100">
                        {{ round($persentaseTerbayar) }}%
                    </div>
                </div>
                <small>Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</small>
            @else
                Rp {{ number_format($totalTerbayar, 0, ',', '.') }}
            @endif
        </td>
        <td>
            <span class="badge {{ getStatusBadgeClass($pinjaman->status) }}">{{ $pinjaman->status }}</span>
        </td>
        <td class="text-center">
            <a href="{{ route('admin.pinjaman.show', $pinjaman->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('admin.pinjaman.invoice', $pinjaman->id) }}" class="btn btn-sm btn-warning" title="Cetak Invoice" target="_blank">
                <i class="bi bi-printer"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-4">Tidak ada data pinjaman dengan status ini.</td>
    </tr>
@endforelse
