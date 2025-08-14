<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pinjaman - {{ $pinjaman->user->id_anggota }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #4e5bf2; }
        .header p { margin: 5px 0; }
        .content { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-section { text-align: right; }
        .total-section h4 { margin: 5px 0; }
        .footer { text-align: center; margin-top: 40px; font-size: 10px; color: #777; }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.1);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    @if($pinjaman->status == 'lunas')
        <div class="watermark">LUNAS</div>
    @elseif($pinjaman->status == 'ditolak')
        <div class="watermark">DITOLAK</div>
    @endif

    <div class="container">
        <div class="header">
            <h1>INVOICE PINJAMAN</h1>
            <p>Koperasi Kosgoro</p>
        </div>

        <table>
            <tr>
                <th style="width: 25%;">Nama Anggota</th>
                <td>{{ $pinjaman->user->nama }}</td>
            </tr>
            <tr>
                <th>ID Anggota</th>
                <td>{{ $pinjaman->user->id_anggota }}</td>
            </tr>
            <tr>
                <th>Tanggal Pengajuan</th>
                <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pengajuan)->format('d F Y') }}</td>
            </tr>
        </table>

        <div class="content">
            <h4>Rincian Pinjaman</h4>
            <table>
                <tr>
                    <th style="width: 50%;">Pinjaman Pokok</th>
                    <td style="text-align: right;">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Bunga ({{ $pinjaman->persentase_bunga * 100 }}%)</th>
                    <td style="text-align: right;">Rp {{ number_format($pinjaman->total_tagihan - $pinjaman->jumlah_pinjaman, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold;">
                    <th>Total Tagihan</th>
                    <td style="text-align: right;">Rp {{ number_format($pinjaman->total_tagihan, 0, ',', '.') }}</td>
                </tr>
            </table>

            @if($pinjaman->angsuran->isNotEmpty())
                <h4>Riwayat Angsuran</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Angsuran Ke-</th>
                            <th>Tanggal Bayar</th>
                            <th style="text-align: right;">Jumlah Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pinjaman->angsuran as $angsuran)
                        <tr>
                            <td>{{ $angsuran->angsuran_ke }}</td>
                            <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d F Y') }}</td>
                            <td style="text-align: right;">Rp {{ number_format($angsuran->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="total-section">
                <h4>Total Terbayar: Rp {{ number_format($pinjaman->angsuran->sum('jumlah_bayar'), 0, ',', '.') }}</h4>
                <h4 style="color: #dc3545;">Sisa Tagihan: Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis oleh sistem Koperasi Kosgoro pada {{ now()->format('d F Y H:i') }}.</p>
        </div>
    </div>
</body>
</html>
