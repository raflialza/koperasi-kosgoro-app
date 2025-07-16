<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Koperasi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-blue-900 text-white p-4 mb-6">
        <div class="container mx-auto">
            <span class="font-bold">Koperasi Kosgoro</span>
        </div>
    </nav>

    <main class="container mx-auto px-4">
        @yield('content')
    </main>
</body>
</html>
