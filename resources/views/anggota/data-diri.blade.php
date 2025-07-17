@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Profile</h2>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <strong>ID Anggota:</strong> {{ Auth::user()->id_anggota }}</div>
        <div>
            <strong>Nama:</strong> {{ Auth::user()->nama }}
        </div>
        <div>
            <strong>Email:</strong> {{ Auth::user()->email }}
        </div>
        <div>
            <strong>No. Telepon:</strong> {{ Auth::user()->no_telp }}
        </div>
        <div>
            <strong>Alamat:</strong> {{ Auth::user()->alamat }}
        </div>
        <div>
            <strong>Instansi:</strong> {{ Auth::user()->instansi }}
        </div>
        <div>
            <strong>Tahun Bergabung:</strong> {{ Auth::user()->tahun_gabung }}
        </div>
    </div>
</div>
@endsection
