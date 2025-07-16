@extends('layouts.app') {{-- atau layout kamu sendiri --}}

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Kelola Anggota</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('anggota.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Anggota</a>

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
            <tr class="border">
                <td class="p-2 border">{{ $a->id_anggota }}</td>
                <td class="p-2 border">{{ $a->nama }}</td>
                <td class="p-2 border">{{ $a->email }}</td>
                <td class="p-2 border">{{ $a->no_telp }}</td>
                <td class="p-2 border">{{ $a->alamat }}</td>
                <td class="p-2 border">{{ $a->instansi }}</td>
                <td class="p-2 border">{{ $a->tahun_gabung }}</td>
                <td class="p-2 border space-x-2">
                    <a href="{{ route('anggota.edit', $a->id) }}" class="bg-yellow-400 text-white px-2 py-1 rounded">Edit</a>

                    @if(auth()->user()->role === 'super_admin')
                    <form action="{{ route('anggota.destroy', $a->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin hapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
