<?php

namespace App\Exports;

use App\Models\Simpanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SimpananExport implements FromCollection, WithHeadings, WithMapping
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
            'Tanggal',
            'ID Anggota',
            'Nama Anggota',
            'Jenis Simpanan',
            'Jumlah',
            'Keterangan',
        ];
    }

    /**
     * @param mixed $simpanan
     *
     * @return array
     */
    public function map($simpanan): array
    {
        return [
            $simpanan->created_at->format('d-m-Y H:i'),
            $simpanan->user->id_anggota,
            $simpanan->user->nama,
            $simpanan->jenis_simpanan,
            $simpanan->jumlah,
            $simpanan->keterangan,
        ];
    }
}
