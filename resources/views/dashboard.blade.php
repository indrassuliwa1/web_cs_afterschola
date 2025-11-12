<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col">
        <div class="flex items-center justify-center py-6 border-b border-blue-800">
            <img src="{{ asset('images/logo-afterschola.png') }}" alt="Logo" class="w-16">
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ url('/home') }}" 
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-800 {{ request()->is('home') ? 'bg-blue-800' : '' }}">
                <i class="fa-solid fa-house"></i> Home
            </a>
            <a href="{{ url('/kontrak') }}" 
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-800 {{ request()->is('kontrak') ? 'bg-blue-800' : '' }}">
                <i class="fa-solid fa-file-contract"></i> Kontrak
            </a>
            <a href="{{ url('/pembayaran') }}" 
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-800 {{ request()->is('pembayaran') ? 'bg-blue-800' : '' }}">
                <i class="fa-solid fa-credit-card"></i> Pembayaran
            </a>
            <a href="{{ url('/informasi') }}" 
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-800 {{ request()->is('informasi') ? 'bg-blue-800' : '' }}">
                <i class="fa-solid fa-circle-info"></i> Informasi
            </a>
            <a href="{{ url('/kelas') }}" 
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-800 {{ request()->is('kelas') ? 'bg-blue-800' : '' }}">
                <i class="fa-solid fa-chalkboard"></i> Kelas
            </a>
        </nav>

        <div class="p-4 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-blue-800 text-left">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col">
        <header class="flex items-center justify-between bg-white p-4 shadow-sm">
            <h1 class="text-2xl font-semibold">@yield('page', 'Overview')</h1>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" placeholder="Cari sesuatu..." 
                        class="border rounded-full px-4 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400"></i>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-700 font-medium">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                    <img src="https://i.pravatar.cc/40" alt="Profile" class="w-10 h-10 rounded-full border">
                </div>
            </div>
        </header>

        <section class="p-6 flex-1">
            @if (isset($message))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-md">
                    <h1 class="text-lg font-semibold">{{ $message }}</h1>
                </div>
            @endif

            @yield('content')
        </section>
    </main>

</body>
</html>
