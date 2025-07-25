<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Dashboard</title>
    
    <link rel="stylesheet" href="{{ asset('css/reponsif-style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background-color: #f8f9fe;
    }
    .sidebar {
        min-height: 100vh;
        background-color: #5e72e4;
        color: white;
        width: 250px;
        flex-shrink: 0; 
    }
    .sidebar a {
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .sidebar .nav-link {
        color: #cfd8ff;
        transition: color 0.3s ease, font-weight 0.3s ease;
    }
    .sidebar .nav-link:hover {
        color: #ffffff;
        font-weight: 600;
    }
    .sidebar .collapse .nav-link.text-white-50 {
        color: #ffffff !important;
    }
    .sidebar .collapse .nav-link.text-white-50:hover {
        color: #ffffff !important;
        font-weight: bold;
    }
    .sidebar .active {
        background-color: #3c4cad;
        border-radius: 10px;
        color: #ffffff;  
    }
    /* Tambahan untuk sidebar responsif */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        height: 100vh;
        width: 250px;
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
        height: 100vh;
        width: 100vw;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 1049;
    }
}
</style>
</head>
<body>
<div class="container-fluid px-0">
    <div class="d-flex" id="applayout">

        <!-- Sidebar -->
        <nav class="sidebar p-4 min-vh-100" id="sidebar">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-box-fill fs-3 me-2"></i>
                <span class="fs-1 fw-bold">Koperasi</span>
            </div>
            @php $role = auth()->user()->role ?? 'guest'; @endphp
            <ul class="nav flex-column">
                @if($role === 'anggota')
                <li class="nav-item">
                    <a href="{{ route('anggota.dataDiri') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('anggota.dataDiri') ? 'active' : '' }}">
                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('anggota.simpanan.riwayat') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('anggota.simpanan.*') ? 'active' : '' }}">
                        Simpanan Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('anggota.pinjaman.riwayat') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('anggota.pinjaman.*') ? 'active' : '' }}">
                        Pinjaman Saya
                    </a>
                </li>
                @endif
                @if($role === 'admin' || $role === 'super_admin')
                  <li class="nav-item">
                      <a href="{{ route('home') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                          <i class="bi bi-house-door me-2"></i>Home
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.anggota.index') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                          <i class="bi bi-people me-2"></i>Data Anggota
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.simpanan.index') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.simpanan.*') ? 'active' : '' }}">
                          <i class="bi bi-wallet2 me-2"></i>Kelola Simpanan
                      </a>
                  </li>

                  {{-- Menu Kelola Pinjaman (Collapse) --}} 
                  <li class="nav-item">
                      <a class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.pinjaman.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#pinjamanSubMenu" role="button" aria-expanded="{{ request()->routeIs('admin.pinjaman.*') ? 'true' : 'false' }}" aria-controls="pinjamanSubMenu">
                          <i class="bi bi-cash-coin me-2"></i>Kelola Pinjaman
                      </a>
                      <div class="collapse {{ request()->routeIs('admin.pinjaman.*') ? 'show' : '' }}" id="pinjamanSubMenu">
                          <ul class="nav flex-column ps-4">
                              <li class="nav-item">
                                  <a href="{{ route('admin.pinjaman.pengajuan') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.pinjaman.pengajuan') ? 'fw-bold' : '' }}">Proses Pengajuan</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('admin.pinjaman.semua') }}" class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.pinjaman.semua') ? 'fw-bold' : '' }}">Semua Pinjaman</a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  {{-- Menu Laporan (Collapse) --}}
                  <li class="nav-item">
                      <a class="nav-link text-[#aab5f944] {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#laporanSubMenu" role="button" aria-expanded="{{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }}" aria-controls="laporanSubMenu">
                          <i class="bi bi-file-earmark-text me-2"></i>Laporan
                      </a>
                      <div class="collapse {{ request()->routeIs('admin.laporan.*') ? 'show' : '' }}" id="laporanSubMenu">
                          <ul class="nav flex-column ps-4">
                              <li class="nav-item">
                                  <a href="{{ route('admin.laporan.simpanan') }}" target="_blank" class="nav-link text-white-50">Laporan Simpanan</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('admin.laporan.pinjaman') }}" target="_blank" class="nav-link text-white-50">Laporan Pinjaman</a>
                              </li>
                          </ul>
                      </div>
                  </li>
                @endif
                @if($role === 'super_admin')
                <li class="nav-item">
                    <a href="#" class="nav-link">Manajemen Admin</a>
                </li>
                @endif
            </ul>
        </nav>

        <!-- Overlay saat sidebar tampil -->
        <div class="content-overlay" id="contentOverlay"></div>

        <!-- Content Area -->
        <main class="flex-grow-1 px-md-4 py-3">
            <div class="flex-grow-1">

            <!-- Top Navbar -->
            <nav class="navbar navbar-light bg-blend-lighten rounded shadow-sm mb-4 px-4">
    <div class="d-flex w-100 align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <!-- Tombol toggle hanya muncul di mobile -->
            <button class="btn btn-primary d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand mb-0 h4 m-0">Selamat Datang, {{ auth()->user()->nama ?? 'Guest' }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </div>
</nav>

            @yield('content')
            @yield('scripts')
        </main>
    </div>
</div>

<script src="{{ asset('js/sweetalert-custom.js') }}"></script>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('contentOverlay');

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        });
    });
</script>
</body>
</html>
