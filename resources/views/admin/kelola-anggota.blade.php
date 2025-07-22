@extends('layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-person-plus me-2"></i> Anggota
    </a>
    
<div class="table-container mb-4">
    <div class="table-wrapper rounded shadow-sm border bg-white p-0">
        <div class="table-title d-flex align-items-center justify-content-between bg-primary text-white px-4 py-3 mb-0" style="border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
            <h5 class="mb-0 fw-bold">Daftar Anggota</h5>
            <form method="GET" action="{{ route('admin.anggota.index') }}" class="row g-2 align-items-center mb-0">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <select name="instansi" class="form-select">
                        <option value=""> All</option>
                        <option value="SMP" {{ request('instansi') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ request('instansi') == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="SMK" {{ request('instansi') == 'SMK' ? 'selected' : '' }}>SMK</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-light" title="Filter">
                        <i class="bi bi-funnel-fill"></i>
                    </button>
                </div>
                <div class="col-auto ms-auto">
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-light" title="Reset">
                        <i class="bi bi-arrow-repeat"></i>
                    </a>
                </div>
            </form>
        </div>
        <div class="p-3">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="align-middle text-center">
                            <th scope="col">No</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Institution</th>
                            <th scope="col">Year Joined</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggota as $a)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $a->id_anggota }}</td>
                            <td class="text-start">{{ $a->nama }}</td>
                            <td class="text-start">{{ $a->email }}</td>
                            <td>{{ $a->no_telp }}</td>
                            <td>
                                <span class="badge bg-{{ $a->instansi == 'SMP' ? 'primary' : ($a->instansi == 'SMA' ? 'success' : 'warning') }}">
                                    {{ $a->instansi }}
                                </span>
                            </td>
                            <td>{{ $a->tahun_gabung }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <form method="GET" action="{{ route('admin.anggota.edit', $a->id) }}">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </form>
                                    @if(auth()->user()->role === 'super_admin')
                                    <form id="delete-form-{{ $a->id }}" method="POST" action="{{ route('admin.anggota.destroy', $a->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $a->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data anggota.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@include('components.sweetalert')