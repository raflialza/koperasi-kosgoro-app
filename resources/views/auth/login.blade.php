<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="POST" action="{{ route('login') }}" class="bg-white p-8 rounded shadow-md w-96">
        @csrf
        <h1 class="text-2xl font-bold mb-6 text-center">Login Koperasi</h1>

        @error('email')
            <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">{{ $message }}</div>
        @enderror

        <div class="mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" id="email" required
                   class="w-full border px-4 py-2 rounded mt-1">
        </div>

        <div class="mb-6">
            <label for="password" class="block font-semibold">Password</label>
            <input type="password" name="password" id="password" required
                   class="w-full border px-4 py-2 rounded mt-1">
        </div>

        <button type="submit"
                class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700">Login</button>
    </form>
</body>
</html>
