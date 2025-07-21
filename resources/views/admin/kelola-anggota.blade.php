@extends('layouts.app')

@section('content')
<div class="p-6" x-data="{ search: '', instansi: '' }">
    <h1 class="text-2xl font-bold mb-4">Kelola Anggota</h1>

    <!-- Tombol Tambah Anggota -->
    <a href="{{ route('admin.anggota.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Anggota</a>

    <!-- Filter -->
    <div class="flex items-center gap-4 mb-4">
        <input type="text" x-model="search" placeholder="Cari" class="border px-4 py-2 rounded w-1/3" />

        <select x-model="instansi" class="border px-4 py-2 rounded">
            <option value="">Semua Instansi</option>
            <option value="SMP">SMP</option>
            <option value="SMA">SMA</option>
            <option value="SMK">SMK</option>
        </select>
    </div>

    <!-- Tabel Data -->
    <table class="w-full table-auto border border-gray-200 mt-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">No. Telp</th>
                <th class="p-2 border">Alamat</th>
                <th class="p-2 border">Instansi</th>
                <th class="p-2 border">Tahun Gabung</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anggota as $a)
            <tr class="border"
                x-show="(search === '' || '{{ strtolower($a->nama) }} {{ strtolower($a->email) }} {{ $a->no_telp }}'.toLowerCase().includes(search.toLowerCase())) && 
                        (instansi === '' || instansi === '{{ $a->instansi }}')">
                <td class="p-2 border">{{ $a->id_anggota }}</td>
                <td class="p-2 border">{{ $a->nama }}</td>
                <td class="p-2 border">{{ $a->email }}</td>
                <td class="p-2 border">{{ $a->no_telp }}</td>
                <td class="p-2 border">{{ $a->alamat }}</td>
                <td class="p-2 border">{{ $a->instansi }}</td>
                <td class="p-2 border">{{ $a->tahun_gabung }}</td>
                <td class="p-2 border space-x-2">
                    <a href="{{ route('admin.anggota.edit', $a->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>

                    @if(auth()->user()->role === 'super_admin')
                    <form x-data="{ open: false }" @submit.prevent="open = true" method="POST" action="{{ route('admin.anggota.destroy', $a->id) }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 mt-1">Hapus</button>

                        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-[350px] text-center">
                                <h2 class="text-xl font-semibold text-red-600 mb-2">Peringatan!</h2>
                                <p class="mb-4 text-gray-700">Yakin ingin menghapus data ini?</p>
                                <div class="flex justify-center gap-4">
                                    <button type="button" @click="open = false" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                                    <button type="button" @click="$el.closest('form').submit()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Alert Success -->
@if(session('success'))
<div x-data="{ open: true }" x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg p-8 w-[350px] text-center shadow-xl">
        <div class="mx-auto w-20 h-20 flex items-center justify-center rounded-full bg-green-500 mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Success!</h2>
        <p class="text-gray-600 mb-6">{{ session('success') }}</p>
        <button @click="open = false" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition">OK</button>
    </div>
</div>
@endif
@endsection
