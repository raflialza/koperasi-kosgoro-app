<!-- resources/views/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dashboard')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

  <!-- Sidebar -->
  <aside class="from-[#253B80] bg-gradient-to-b to-[#179BD7] flex flex-col items-start p-6 h-screen text-white w-64">
    <h1 class="text-xl font-bold mb-6 text-center">Koperasi Kosgoro</h1>
    @php $role = auth()->user()->role ?? 'guest'; @endphp
    <nav class="space-y-2">
      @if($role === 'anggota')
        <a href="{{ route('anggota.dataDiri') }}"
   class="block p-2 rounded transition duration-200
          {{ request()->routeIs('anggota.dataDiri') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
  Profile
</a>

<a href="{{ route('anggota.simpanan') }}"
   class="block p-2 rounded transition duration-200
          {{ request()->routeIs('anggota.simpanan') ? 'text-white' : 'text-gray-400 hover:text-white' }}">
  Simpanan Saya
</a>
        <a href="#" class="block p-2 rounded transition duration-200 text-gray-400 hover:text-white">Pinjaman Saya</a>
      @endif

      @if(in_array($role, ['admin', 'super_admin']))
        <a href="{{ route('anggota.index') }}" class="block p-2 rounded transition duration-200 text-gray-400 hover:text-white">Kelola Anggota</a>
        <a href="#" class="block p-2 rounded transition duration-200 text-gray-400 hover:text-white">Input Simpanan</a>
        <a href="#" class="block p-2 rounded transition duration-200 text-gray-400 hover:text-white">Kelola Pinjaman</a>
        <a href="#" class="block p-2 rounded transition duration-200 text-gray-400 hover:text-white">Laporan Keuangan</a>
      @endif

      @if($role === 'super_admin')
        <a href="#" class="block p-2 rounded transition duration-200 text-gray-400 hover:text-white">Manajemen Admin</a>
      @endif
    </nav>
  </aside>

  <!-- Konten -->
  <div class="flex-1 flex flex-col">
    <!-- Navbar -->
    <header class=" bg-[#FF6000] shadow px-6 py-4 flex justify-between items-center">
      <h2 class="text-xl font-semibold">@yield('title', 'Dashboard')</h2>
      <div class="flex items-center gap-4">
        <span class="text-gray-700">{{ auth()->user()->nama ?? 'Guest' }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Logout</button>
        </form>
      </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
      @yield('content')
    </main>
  </div>

</body>
</html>
