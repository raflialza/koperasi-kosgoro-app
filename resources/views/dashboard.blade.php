@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Selamat Datang, {{ auth()->user()->nama }}!</h1>

  @if(auth()->user()->role === 'anggota')
    <p>Ini adalah dashboard anggota. Kamu bisa melihat simpanan dan pinjamanmu.</p>
  @elseif(auth()->user()->role === 'admin')
    <p>Ini adalah dashboard admin. Kamu bisa mengelola anggota, simpanan, dan pinjaman.</p>
  @elseif(auth()->user()->role === 'super_admin')
    <p>Ini adalah dashboard super admin. Kamu punya akses penuh ke semua fitur.</p>
  @endif
@endsection
