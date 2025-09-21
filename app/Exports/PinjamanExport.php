<?php

namespace App\Exports;

use App\Models\Pinjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PinjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal Pengajuan',
            'Tanggal Disetujui',
            'ID Anggota',
            'Nama Anggota',
            'Pinjaman Pokok',
            'Margin (%)',
            'Total Tagihan',
            'Tenor (Bulan)',
            'Status',
        ];
    }

    /**
     * @param mixed $pinjaman
     *
     * @return array
     */
    public function map($pinjaman): array
    {
        $totalMargin = $pinjaman->jumlah_pinjaman * ($pinjaman->margin / 100);
        $totalTagihan = $pinjaman->jumlah_pinjaman + $totalMargin;

        return [
            $pinjaman->tanggal_pengajuan,
            $pinjaman->tanggal_disetujui,
            $pinjaman->user->id_anggota,
            $pinjaman->user->nama,
            $pinjaman->jumlah_pinjaman,
            $pinjaman->margin,
            $totalTagihan,
            $pinjaman->tenor,
            $pinjaman->status,
        ];
    }
}
