<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons (Bootstrap Icons or FontAwesome) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #ffd369;
        }
        .sidebar .active {
            background-color: #3c4cad;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar p-4">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-box-fill fs-4 me-2"></i>
                <span class="fs-5 fw-bold">Koperasi</span>
            </div>
            @php $role = auth()->user()->role ?? 'guest'; @endphp
            <ul class="nav flex-column">
      @if($role === 'anggota')
        <li class="nav-item">
          <a href="{{ route('anggota.dataDiri') }}"
             class="nav-link text-white {{ request()->routeIs('anggota.dataDiri') ? 'active' : '' }}">
            Profil
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('anggota.simpanan') }}"
             class="nav-link text-white {{ request()->routeIs('anggota.simpanan') ? 'active' : '' }}">
            Simpanan Saya
          </a>
        </li>
        <li class="nav-item">
          <a href="#"
             class="nav-link text-white">
            Pinjaman Saya
          </a>
        </li>
      @endif

      @if(in_array($role, ['admin', 'super_admin']))
        <li class="nav-item">
          <a href="#" class="nav-link text-white {{ request()->routeIs('admin.home') ? 'active' : '' }}">Home</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.anggota.index') }}" class="nav-link text-white {{ request()->routeIs('admin.anggota.index') ? 'active' : '' }}">Kelola Anggota</a>
        @if($role === 'super_admin')
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Manajemen Admin</a>
        </li>
        @endif
        </li>
        <li class="nav-item">
          <a href="#"class="nav-link text-white">Input Simpanan</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Kelola Pinjaman</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Laporan Keuangan</a>
          </a>
        </li>
      @endif
    </ul>
        </nav>

        <!-- Content Area -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Top Navbar -->
            <nav class="navbar navbar-light bg-white rounded shadow-sm mb-4 px-4">
                <span class="navbar-brand mb-0 h4">Selamat Datang, {{ auth()->user()->nama ?? 'Guest' }}</span>
                <div class="d-flex">
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                    </form>
                </div>
            </nav>

            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap 5 JS (Popper + Bootstrap Bundle) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
