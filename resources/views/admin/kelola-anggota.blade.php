@extends('layouts.app')

@section('content')
<div class="container py-4" x-data="{ search: '', instansi: '' }">
    <h1 class="h3 mb-4 fw-bold">Kelola Anggota</h1>

    <!-- Tombol Tambah -->
    <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary mb-3">Tambah Anggota</a>

    <!-- Filter -->
    <div class="row mb-3 g-2">
        <div class="col-md-4">
            <input type="text" x-model="search" placeholder="Cari" class="form-control" />
        </div>
        <div class="col-md-3">
            <select x-model="instansi" class="form-select">
                <option value="">Semua Instansi</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="SMK">SMK</option>
            </select>
        </div>
    </div>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telp</th>
                    <th>Alamat</th>
                    <th>Instansi</th>
                    <th>Tahun Gabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anggota as $a)
                <tr x-show="(search === '' || '{{ strtolower($a->nama) }} {{ strtolower($a->email) }} {{ $a->no_telp }}'.toLowerCase().includes(search.toLowerCase())) && 
                            (instansi === '' || instansi === '{{ $a->instansi }}')">
                    <td>{{ $a->id_anggota }}</td>
                    <td>{{ $a->nama }}</td>
                    <td>{{ $a->email }}</td>
                    <td>{{ $a->no_telp }}</td>
                    <td>{{ $a->alamat }}</td>
                    <td>{{ $a->instansi }}</td>
                    <td>{{ $a->tahun_gabung }}</td>
                    <td>
                        <a href="{{ route('admin.anggota.edit', $a->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>

                        @if(auth()->user()->role === 'super_admin')
                        <form x-data="{ open: false }" @submit.prevent="open = true" method="POST" action="{{ route('admin.anggota.destroy', $a->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
