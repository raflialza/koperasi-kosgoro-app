<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SHU Tahun {{ $tahun }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .container { width: 100%; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 22px; color: #2c3e50; }
        .header p { margin: 5px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        thead th { background-color: #f2f7ff; color: #333; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 9px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pembagian Sisa Hasil Usaha (SHU)</h1>
        <p>Koperasi Kosgoro</p>
        <p><strong>Tahun Buku: {{ $tahun }}</strong></p>
    </div>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th class="text-right">Total Simpanan (Pokok+Wajib)</th>
                    <th class="text-right">Bagian SHU</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataShu as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data['id_anggota'] }}</td>
                    <td>{{ $data['nama'] }}</td>
                    <td class="text-right">Rp {{ number_format($data['total_simpanan'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['bagian_shu'], 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data anggota untuk perhitungan SHU.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            <strong>Total SHU yang Dibagikan:</strong>
            <strong style="float: right;">Rp {{ number_format($totalShu, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y, H:i:s') }}
    </div>
</body>
</html>
