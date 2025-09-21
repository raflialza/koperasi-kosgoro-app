<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Koperasi Kosgoro') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ secure_asset('css/modern-table.css') }}" rel="stylesheet">
    
    @stack('styles')

    <style>
        body {
            background-color: #f8f9fe;
            font-family: 'Figtree', sans-serif; /* Font lebih modern */
        }

        /* Sidebar style */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e5bf2 0%, #1b1f4d 100%);
            color: white;
            width: 250px;
            flex-shrink: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar .sidebar-brand-icon i {
            font-size: 2rem;
        }
        
        .sidebar .sidebar-brand-text {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .sidebar .nav-link {
            color: #dbe2ff;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #ffffff;
            color: #4e5bf2;
            font-weight: 600;
        }

        .sidebar .collapse .nav-link {
            padding-left: 2.5rem; /* Indentasi submenu */
            color: rgba(255, 255, 255, 0.7);
        }
        
        .sidebar .collapse .nav-link:hover,
        .sidebar .collapse .nav-link.fw-bold {
            color: #ffffff;
        }

        /* Top Navbar style */
        .navbar-top {
            background-color: #ffffff;
            border: none;
        }

        #sidebarToggle {
            background-color: #4e5bf2;
            border: none;
            color: white;
        }

        .main-content {
            width: 100%;
            transition: padding-left 0.3s ease-in-out;
        }

        /* Sidebar responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1050;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .content-overlay {
                display: none;
            }
            .content-overlay.active {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1049;
            }
        }
    </style>
</head>
<body>
<div class="d-flex" id="applayout">

    <nav class="sidebar p-3" id="sidebar">
        <div class="d-flex align-items-center mb-4 p-2">
            <img src="{{ asset('images/logokosgoro.png') }}" alt="Logo" style="height: 40px; margin-right: 15px;">
            <span class="sidebar-brand-text">Koperasi</span>
        </div>

        @php $role = auth()->user()->role ?? 'guest'; @endphp
        <ul class="nav flex-column gap-2">
            @if($role === 'anggota' || $role === 'admin' || $role === 'super_admin')
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="bi bi-house-door me-3"></i>Dashboard
                    </a>
                </li>
            @endif

            @if($role === 'anggota')
                <li class="nav-item">
                    <a href="{{ route('anggota.dataDiri') }}" class="nav-link {{ request()->routeIs('anggota.dataDiri') || request()->routeIs('anggota.dataDiri') ? 'active' : '' }}"><i class="bi bi-person-circle me-3"></i>Profil Saya</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('anggota.simpanan.riwayat') }}" class="nav-link {{ request()->routeIs('anggota.simpanan.*') ? 'active' : '' }}"><i class="bi bi-wallet2 me-3"></i>Simpanan Saya</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('anggota.pinjaman.riwayat') }}" class="nav-link {{ request()->routeIs('anggota.pinjaman.*') ? 'active' : '' }}"><i class="bi bi-cash-coin me-3"></i>Pinjaman Saya</a>
                </li>
            @endif

            @if($role === 'admin' || $role === 'super_admin')
                <li class="nav-item">
                    <a href="{{ route('admin.anggota.index') }}" class="nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-3"></i>Data Anggota
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.simpanan.*') || request()->routeIs('admin.pinjaman.*') || request()->routeIs('admin.transaksi.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#transaksiSubMenu" role="button" aria-expanded="{{ request()->routeIs('admin.simpanan.*') || request()->routeIs('admin.pinjaman.*') || request()->routeIs('admin.transaksi.*') ? 'true' : 'false' }}">
                        <i class="bi bi-cash-coin me-3"></i>Transaksi
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.simpanan.*') || request()->routeIs('admin.pinjaman.*') || request()->routeIs('admin.transaksi.*') ? 'show' : '' }}" id="transaksiSubMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a href="{{ route('admin.transaksi.semua') }}" class="nav-link {{ request()->routeIs('admin.transaksi.semua') ? 'fw-bold' : '' }}">Semua Transaksi</a></li>
                            <li class="nav-item"><a href="{{ route('admin.simpanan.index') }}" class="nav-link {{ request()->routeIs('admin.simpanan.*') ? 'fw-bold' : '' }}">Kelola Simpanan</a></li>
                            {{-- PERBAIKAN DI SINI --}}
                            <li class="nav-item"><a href="{{ route('admin.pinjaman.index', ['status' => 'Menunggu Persetujuan']) }}" class="nav-link {{ request()->query('status') == 'Menunggu Persetujuan' ? 'fw-bold' : '' }}">Proses Pengajuan</a></li>
                            <li class="nav-item"><a href="{{ route('admin.pinjaman.index') }}" class="nav-link {{ request()->routeIs('admin.pinjaman.index') && !request()->query('status') ? 'fw-bold' : '' }}">Semua Pinjaman</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#laporanSubMenu" role="button" aria-expanded="{{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }}">
                        <i class="bi bi-file-earmark-text me-3"></i>Laporan
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.laporan.*') ? 'show' : '' }}" id="laporanSubMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'fw-bold' : '' }}">Laporan Transaksi</a></li>
                            <li class="nav-item"><a href="{{ route('admin.laporan.shu.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.shu.index') ? 'fw-bold' : '' }}">Laporan SHU</a></li>
                        </ul>
                    </div>
                </li>
            @endif
        </ul>
    </nav>

    <div class="content-overlay" id="contentOverlay"></div>

    <div class="main-content flex-grow-1">
        <nav class="navbar-top rounded shadow-sm mb-4 mx-4 mt-3 p-2">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <button class="btn d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-brand mb-0 h4 ms-2">Selamat Datang, {{ auth()->user()->nama ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger me-2">Logout</button>
                </form>
            </div>
        </nav>

        <main class="px-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ secure_asset('js/koperasi.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('contentOverlay');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        });
    }
});
</script>

@stack('scripts')
</body>
</html>
