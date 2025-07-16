@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-blue-900 text-white flex-shrink-0">
        <div class="p-6 font-bold text-xl border-b border-blue-700">Koperasi</div>
        <nav class="p-4 space-y-2">

            {{-- Menu untuk Anggota --}}
            @if(auth()->user()->role === 'anggota')
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700">Lihat Simpanan</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700">Lihat Pinjaman</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700">Ajukan Pinjaman</a>
            @endif

            {{-- Menu untuk Admin dan Super Admin --}}
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                <a href="{{ route('anggota.index') }}" class="block px-4 py-2 rounded hover:bg-blue-700">Kelola Anggota</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700">Kelola Simpanan</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700">Kelola Pinjaman</a>
            @endif

            {{-- Menu khusus Super Admin --}}
            @if(auth()->user()->role === 'super_admin')
                <hr class="border-blue-700 my-2">
                <a href="#" class="block px-4 py-2 rounded hover:bg-red-600 text-red-200">Manajemen Admin</a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 mt-4 rounded hover:bg-blue-700">Logout</button>
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-6 bg-gray-100">
        <h1 class="text-2xl font-bold mb-4">Halo, {{ auth()->user()->nama }}</h1>
        <p class="text-gray-700">Selamat datang di sistem informasi koperasi.</p>

        {{-- Kamu bisa tampilkan komponen dinamis di sini --}}
        @if(session('success'))
            <div class="mt-4 bg-green-200 text-green-800 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif
    </main>
</div>
@endsection
