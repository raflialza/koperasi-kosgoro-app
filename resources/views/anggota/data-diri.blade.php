@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/datadiri-style.css') }}">

<div class="container">
    <h1 class="title">Profile</h1>
    <p class="subtitle">View all your profile details here.</p>

    <div class="profile-card">
        <!-- Left: Foto dan Nama -->
        <div class="profile-left">
            <div class="profile-image">
                <img src="{{ asset('images/datadiri.jpeg') }}" alt="Profile Picture">
            </div>
            <h2 class="name">{{ Auth::user()->nama }}</h2>
            <span class="status">Anggota Aktif</span>
        </div>

        <!-- Right: Detail -->
        <div class="profile-right">
            <div class="info-grid">
                <div><span>ID Anggota</span>{{ Auth::user()->id_anggota }}</div>
                <div><span>Email</span>{{ Auth::user()->email }}</div>

                <div><span>No. Telepon</span>{{ Auth::user()->no_telp }}</div>
                <div><span>Alamat</span>{{ Auth::user()->alamat }}</div>

                <div><span>Instansi</span>{{ Auth::user()->instansi }}</div>
                <div><span>Tahun Bergabung</span>{{ Auth::user()->tahun_gabung }}</div>

                <div><span>Status</span><span class="availability">● Aktif</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
