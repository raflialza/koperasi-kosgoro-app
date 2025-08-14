<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Simpanan Koperasi</title>
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
        .summary-table { width: 50%; float: right; margin-top: 20px; }
        .summary-table td { border: 1px solid #ccc; }
        .summary-table .label { font-weight: bold; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 9px; color: #888; }
        .page-break { page-break-after: always; }
        .badge {
            padding: 3px 7px;
            border-radius: 12px;
            font-size: 10px;
            color: #fff;
        }
        .bg-danger { background-color: #dc3545; }
        .bg-primary { background-color: #0d6efd; }
        .bg-success { background-color: #198754; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Simpanan Anggota</h1>
        <p>Koperasi Kosgoro</p>
        @if($startDate && $endDate)
            <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        @else
            <p><strong>Periode:</strong> Keseluruhan</p>
        @endif
    </div>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Tanggal Transaksi</th>
                    <th>Jenis Simpanan</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semuaSimpanan as $simpanan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $simpanan->user->id_anggota }}</td>
                    <td>{{ $simpanan->user->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($simpanan->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td>
                        @php
                            $badgeColor = '';
                            if ($simpanan->jenis_simpanan == 'Pokok') $badgeColor = 'bg-danger';
                            if ($simpanan->jenis_simpanan == 'Wajib') $badgeColor = 'bg-primary';
                            if ($simpanan->jenis_simpanan == 'Sukarela') $badgeColor = 'bg-success';
                        @endphp
                        <span class="badge {{ $badgeColor }}">{{ $simpanan->jenis_simpanan }}</span>
                    </td>
                    <td class="text-right">Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data simpanan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td class="label">Total Simpanan Pokok</td>
                <td class="text-right">Rp {{ number_format($totalPokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Simpanan Wajib</td>
                <td class="text-right">Rp {{ number_format($totalWajib, 0, ',', '.') }}</td>
            </tr>
             <tr>
                <td class="label">Total Simpanan Sukarela</td>
                <td class="text-right">Rp {{ number_format($totalSukarela, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f2f7ff;">
                <td class="label">Total Keseluruhan Simpanan</td>
                <td class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y, H:i:s') }}
    </div>
</body>
</html>
