<!DOCTYPE html>
<html>
<head>
    <title>Laporan Semua Simpanan</title>
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
    <h2>Laporan Semua Transaksi Simpanan</h2>
    <table>
        <thead>
            <tr>
                <th>ID Anggota</th>
                <th>Nama Anggota</th>
                <th>Jenis</th>
                <th>Tanggal</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semuaSimpanan as $simpanan)
            <tr>
                <td>{{ $simpanan->user->id_anggota }}</td>
                <td>{{ $simpanan->user->nama }}</td>
                <td style="text-transform: capitalize;">{{ $simpanan->jenis_simpanan }}</td>
                <td>{{ \Carbon\Carbon::parse($simpanan->tanggal_transaksi)->format('d-m-Y') }}</td>
                <td style="text-align: right;">{{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Ringkasan</h3>
    <table class="summary">
        <tr>
            <td>Total Simpanan Pokok</td>
            <td style="text-align: right;">{{ number_format($totalPokok, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Simpanan Wajib</td>
            <td style="text-align: right;">{{ number_format($totalWajib, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Semua Simpanan</th>
            <th style="text-align: right;">{{ number_format($totalSemua, 0, ',', '.') }}</th>
        </tr>
    </table>
</body>
</html>