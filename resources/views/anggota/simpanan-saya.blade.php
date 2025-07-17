@extends('layouts.app')

@section('title', 'Simpanan Saya')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Simpanan Saya</h1>

    @if ($simpanan->isEmpty())
        <p class="text-gray-600">Belum ada data simpanan.</p>
    @else
        @php
            $grouped = $simpanan->groupBy('tahun');
        @endphp

        @foreach ($grouped as $tahun => $items)
            <h2 class="text-xl font-semibold mt-6 mb-2">Tahun {{ $tahun }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border border-gray-300 text-sm mb-6">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="p-2 border">Bulan</th>
                            <th class="p-2 border">Jenis</th>
                            <th class="p-2 border">Jumlah</th>
                            <th class="p-2 border">Saldo Awal</th>
                            <th class="p-2 border">Saldo Akhir</th>
                            <th class="p-2 border">Tanggal</th>
                            <th class="p-2 border">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $s)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-2 border">{{ DateTime::createFromFormat('!m', $s->bulan)->format('F') }}</td>
                                <td class="p-2 border capitalize">{{ $s->jenis_simpanan }}</td>
                                <td class="p-2 border">Rp{{ number_format($s->jumlah, 0, ',', '.') }}</td>
                                <td class="p-2 border">Rp{{ number_format($s->saldo_awal, 0, ',', '.') }}</td>
                                <td class="p-2 border">Rp{{ number_format($s->saldo_akhir, 0, ',', '.') }}</td>
                                <td class="p-2 border">{{ \Carbon\Carbon::parse($s->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="p-2 border">{{ $s->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
