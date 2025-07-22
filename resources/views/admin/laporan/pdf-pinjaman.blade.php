<!DOCTYPE html>
<html>
<head>
    <title>Laporan Semua Pinjaman</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .summary { margin-top: 20px; width: 40%; border-collapse: collapse; }
        .summary td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <h2>Laporan Semua Transaksi Pinjaman</h2>
    <table>
        <thead>
            <tr>
                <th>ID Anggota</th>
                <th>Nama Anggota</th>
                <th>Tgl Pengajuan</th>
                <th>Tgl Disetujui</th>
                <th>Status</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semuaPinjaman as $pinjaman)
            <tr>
                <td>{{ $pinjaman->user->id_anggota }}</td>
                <td>{{ $pinjaman->user->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d-m-Y') }}</td>
                <td>{{ $pinjaman->tanggal_disetujui ? \Carbon\Carbon::parse($pinjaman->tanggal_disetujui)->format('d-m-Y') : '-' }}</td>
                <td style="text-transform: capitalize;">{{ $pinjaman->status }}</td>
                <td style="text-align: right;">{{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Ringkasan</h3>
    <table class="summary">
        <tr>
            <td>Total Pinjaman Aktif & Lunas</td>
            <td style="text-align: right;">{{ number_format($totalPinjaman, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Jumlah Pinjaman Disetujui</td>
            <td style="text-align: right;">{{ $pinjamanDisetujui }}</td>
        </tr>
         <tr>
            <td>Jumlah Pinjaman Lunas</td>
            <td style="text-align: right;">{{ $pinjamanLunas }}</td>
        </tr>
    </table>
</body>
</html>