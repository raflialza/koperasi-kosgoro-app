<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pinjaman Koperasi</title>
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
        .bg-success { background-color: #28a745; }
        .bg-info { background-color: #17a2b8; }
        .bg-warning { background-color: #ffc107; color: #212529; }
        .bg-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pinjaman Anggota</h1>
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
                    <th>Tgl Pengajuan</th>
                    <th class="text-right">Pinjaman Pokok</th>
                    <th class="text-right">Bunga</th>
                    <th class="text-right">Total Tagihan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semuaPinjaman as $pinjaman)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pinjaman->user->id_anggota }}</td>
                    <td>{{ $pinjaman->user->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d-m-Y') }}</td>
                    <td class="text-right">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($pinjaman->total_tagihan - $pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($pinjaman->total_tagihan, 0, ',', '.') }}</td>
                    <td>
                        @if($pinjaman->status == 'disetujui')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($pinjaman->status == 'lunas')
                            <span class="badge bg-info">Lunas</span>
                        @elseif($pinjaman->status == 'menunggu')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif($pinjaman->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data pinjaman pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td class="label">Total Pinjaman Pokok</td>
                <td class="text-right">Rp {{ number_format($totalPokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Bunga Pinjaman</td>
                <td class="text-right">Rp {{ number_format($totalBunga, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f2f7ff;">
                <td class="label">Total Keseluruhan Tagihan</td>
                <td class="text-right">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y, H:i:s') }}
    </div>
</body>
</html>
