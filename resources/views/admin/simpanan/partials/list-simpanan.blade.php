{{-- File ini HANYA berisi baris-baris tabel (tr) --}}
@forelse ($semuaSimpanan as $simpanan)
    <tr>
        <td>
            <strong>{{ $simpanan->user->nama }}</strong><br>
            <small class="text-muted">{{ $simpanan->user->id_anggota }}</small>
        </td>
        <td>{{ \Carbon\Carbon::parse($simpanan->tanggal_transaksi)->format('d M Y') }}</td>
        <td>
            @php
                $badgeColor = 'bg-secondary';
                if ($simpanan->jenis_simpanan == 'Pokok') $badgeColor = 'bg-danger';
                if ($simpanan->jenis_simpanan == 'Wajib') $badgeColor = 'bg-primary';
                if ($simpanan->jenis_simpanan == 'Sukarela') $badgeColor = 'bg-success';
            @endphp
            <span class="badge {{ $badgeColor }}">{{ $simpanan->jenis_simpanan }}</span>
        </td>
        <td class="text-end"><strong>Rp{{ number_format($simpanan->jumlah, 0, ',', '.') }}</strong></td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">Data simpanan tidak ditemukan.</td>
    </tr>
@endforelse
