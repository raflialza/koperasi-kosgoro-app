<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="from-[#253B80] bg-gradient-to-bl to-[#179BD7] flex justify-center items-center h-screen">
    <form method="POST" action="{{ route('login') }}" class="bg-white border-2 border-[#FF6000] p-8 rounded-xl w-96 shadow-lg">
        @csrf
        <h1 class="text-2xl font-bold mb-6 text-[#000000] text-center">USER LOGIN</h1>

        @error('email')
            <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">{{ $message }}</div>
        @enderror

        <!-- Username input -->
        <div class="mb-4 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-[#FF6000]" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5.121 17.804A13.937 13.937 0 0112 15c2.063 0 4.002.468 5.879 1.304M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            <input type="email" name="email" id="email" required placeholder="Username"
                   class="w-full bg-white border-b border-[#FF6000] text-[#FF6000] pl-10 pr-4 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-[#FF6000] placeholder-gray-500">
        </div>

        <!-- Password input -->
        <div class="mb-6 relative">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-[#FF6000]" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v3H6a2 2 0 00-2 2v5a2 2 0 002 2z"/>
                </svg>
            </span>
            <input type="password" name="password" id="password" required placeholder="Password"
                   class="w-full bg-white border-b border-[#FF6000] text-[#FF6000] pl-4 pr-10 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-[#FF6000] placeholder-gray-500">
        </div>

        <!-- Login button -->
        <button type="submit"
                class="bg-[#FF6000] text-white font-semibold w-full py-2 rounded-full hover:bg-gray-200 transition">LOGIN</button>
    </form>
</body>
</html>
