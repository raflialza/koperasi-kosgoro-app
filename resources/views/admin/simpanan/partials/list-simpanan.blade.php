@forelse ($semuaSimpanan as $simpanan)
    <tr>
        <td>{{ $simpanan->user->id_anggota }}</td>
        <td>{{ $simpanan->user->nama }}</td>
        <td class="text-capitalize">{{ $simpanan->jenis_simpanan }}</td>
        <td>{{ \Carbon\Carbon::parse($simpanan->tanggal_transaksi)->format('d M Y') }}</td>
        <td class="text-end">{{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted">Tidak ada data simpanan</td>
    </tr>
@endforelse