<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Dashboard</title>
    
    <link href="{{ asset('css/custom-style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8f9fe;
        }
        .sidebar {
            height: 100vh;
            background-color: #5e72e4;
            color: white;
        }
        .sidebar a {
            color: #cfd8ff; /* Warna teks normal: agak gelap */
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .sidebar a:hover {
            color: #ffffff; /* Warna teks saat hover: cerah */
        }
        .sidebar .active {
            background-color: #3c4cad;
            border-radius: 10px;
            color: #ffffff; /* Warna teks di item aktif */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar p-4 min-vh-100">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-box-fill fs-3 me-2"></i>
                <span class="fs-2 fw-bold">Koperasi</span>
            </div>
            @php $role = auth()->user()->role ?? 'guest'; @endphp
            <ul class="nav flex-column">
                @if($role === 'anggota')
                <li class="nav-item">
                    <a href="{{ route('anggota.dataDiri') }}" class="nav-link text-white {{ request()->routeIs('anggota.dataDiri') ? 'active' : '' }}">
                        Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('anggota.simpanan.riwayat') }}" class="nav-link text-white {{ request()->routeIs('anggota.simpanan.*') ? 'active' : '' }}">
                        Simpanan Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('anggota.pinjaman.riwayat') }}" class="nav-link text-white {{ request()->routeIs('anggota.pinjaman.*') ? 'active' : '' }}">
                        Pinjaman Saya
                    </a>
                </li>
                @endif
                @if($role === 'admin' || $role === 'super_admin')
                  <li class="nav-item">
                      <a href="#" class="nav-link text-white {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                          <i class="bi bi-house-door me-2"></i>Home
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.anggota.index') }}" class="nav-link text-white {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                          <i class="bi bi-people me-2"></i>Data Anggota
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.simpanan.index') }}" class="nav-link text-white {{ request()->routeIs('admin.simpanan.*') ? 'active' : '' }}">
                          <i class="bi bi-wallet2 me-2"></i>Kelola Simpanan
                      </a>
                  </li>

                  {{-- Menu Kelola Pinjaman (Collapse) --}}
                  <li class="nav-item">
                      <a class="nav-link text-white {{ request()->routeIs('admin.pinjaman.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#pinjamanSubMenu" role="button" aria-expanded="{{ request()->routeIs('admin.pinjaman.*') ? 'true' : 'false' }}" aria-controls="pinjamanSubMenu">
                          <i class="bi bi-cash-coin me-2"></i>Kelola Pinjaman
                      </a>
                      <div class="collapse {{ request()->routeIs('admin.pinjaman.*') ? 'show' : '' }}" id="pinjamanSubMenu">
                          <ul class="nav flex-column ps-4">
                              <li class="nav-item">
                                  <a href="{{ route('admin.pinjaman.pengajuan') }}" class="nav-link text-white-50 {{ request()->routeIs('admin.pinjaman.pengajuan') ? 'fw-bold' : '' }}">Proses Pengajuan</a>
                              </li>
                              <li class="nav-item">
                                  <a href="{{ route('admin.pinjaman.semua') }}" class="nav-link text-white-50 {{ request()->routeIs('admin.pinjaman.semua') ? 'fw-bold' : '' }}">Semua Pinjaman</a>
                              </li>
                          </ul>
                      </div>
                  </li>
                  {{-- Menu Laporan (Collapse) --}}
                  <li class="nav-item">
                      <a class="nav-link text-white {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#laporanSubMenu" role="button" aria-expanded="{{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }}" aria-controls="laporanSubMenu">
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

        <!-- Content Area -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-3">
            <!-- Top Navbar -->
            <nav class="navbar navbar-light bg-blend-lighten rounded shadow-sm mb-4 px-4">
                <span class="navbar-brand mb-0 h4">Selamat Datang, {{ auth()->user()->nama ?? 'Guest' }}</span>
                <div class="d-flex align-items-center rounded ms-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="col-md btn btn-danger ms-sm-auto ">Logout</button>
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
</body>
</html>
