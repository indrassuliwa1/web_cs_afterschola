<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="absolute top-5 left-5">
        <a href="{{ url()->previous() }}" 
           class="flex items-center text-blue-700 font-medium hover:underline">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md w-full max-w-4xl flex p-8">
        <!-- Logo Section -->
        <div class="flex-1 flex flex-col items-center justify-center border-r">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="AfterSchola Logo" class="w-48 mb-4">
            <span class="text-gray-600 font-medium"></span>
        </div>

        <!-- Form Section -->
        <div class="flex-1 px-10 flex flex-col justify-center">
            <h2 class="text-2xl font-bold mb-2">LOGIN</h2>
            <p class="text-gray-600 mb-6">Selamat datang! Silakan masukkan akun anda</p>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 mb-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required 
                           class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required 
                           class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-300 focus:outline-none">
                </div>

                <button type="submit" 
                        class="w-full bg-blue-800 text-white py-2 rounded-md hover:bg-blue-900 transition">
                    Login
                </button>

                <div class="flex justify-between items-center text-sm text-gray-500 mt-2">
                    <div>
                        <input type="checkbox" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="#" class="hover:underline">Lupa password?</a>
                </div>

                <p class="text-center text-sm text-gray-600 mt-4">
                    Belum memiliki akun? 
                    <a href="#" class="text-blue-700 font-medium hover:underline">Register</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>
