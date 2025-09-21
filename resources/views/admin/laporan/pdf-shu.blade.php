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
        <table style="margin-bottom: 30px;">
            <tr>
                <td style="width: 70%;"><strong>Total Pendapatan Jasa/Margin Koperasi</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPendapatanMargin, 2, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td><strong>Total SHU ({{ $alokasi['jasa_modal'] * 100 }}% dari Pendapatan)</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($shu, 2, ',', '.') }}</strong></td>
            </tr>
        </table>

        <h4>Rincian Alokasi SHU</h4>
        <table>
             <tr>
                <td style="width: 70%;">Dana Cadangan ({{ $alokasi['dana_cadangan'] * 100 }}%)</td>
                <td class="text-right">Rp {{ number_format($shuDanaCadangan, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jasa Usaha Anggota ({{ $alokasi['jasa_usaha'] * 100 }}%)</td>
                <td class="text-right">Rp {{ number_format($shuJasaUsaha, 2, ',', '.') }}</td>
            </tr>
             <tr>
                <td>Jasa Modal Anggota ({{ $alokasi['jasa_modal'] * 100 }}%)</td>
                <td class="text-right">Rp {{ number_format($shuJasaModal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Dana Pengurus & Karyawan ({{ $alokasi['dana_pengurus'] * 100 }}%)</td>
                <td class="text-right">Rp {{ number_format($shuDanaPengurus, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Dana Sosial ({{ $alokasi['dana_sosial'] * 100 }}%)</td>
                <td class="text-right">Rp {{ number_format($shuDanaSosial, 2, ',', '.') }}</td>
            </tr>
             <tr style="background-color: #f2f7ff; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($shu, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y, H:i:s') }}
    </div>
</body>
</html>
