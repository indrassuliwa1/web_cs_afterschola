<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    {{-- MENGGUNAKAN CDN TAILWIND --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Konfigurasi Warna Tailwind --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#164370',
                        'secondary-blue': '#103052',
                    }
                }
            }
        }
    </script>

    <style>
        /* Mengunci tinggi body agar sidebar fixed bekerja dengan baik */
        html {
            height: 100%;
        }

        /* Style untuk membuat header tetap di atas konten */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Style tambahan untuk memastikan body tidak overflow horizontal */
        body {
            overflow-x: hidden;
        }

        /* Kustom Shadow (Sesuai gaya admin Anda) */
        .shadow-3xl {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .shadow-4xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body class="bg-gray-100 flex h-full font-sans">

    <aside class="w-64 bg-[#164370] text-white flex flex-col justify-between h-screen fixed top-0 left-0 z-20">
        <div class="flex flex-col flex-1 items-center overflow-y-auto">
            <div class="py-10 px-4 w-full text-center">
                <div class="inline-block p-2 rounded-lg bg-white/10 shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-28 h-auto">
                </div>
            </div>

            <nav class="flex flex-col space-y-5 w-full px-5 pb-5">
                @php
                    $menus = [
                        ['name' => 'Home', 'icon' => 'fa-house', 'route' => 'admin.dashboard'],
                        ['name' => 'Kontrak', 'icon' => 'fa-file-contract', 'route' => 'admin.kontrak'],
                        ['name' => 'Pembayaran', 'icon' => 'fa-credit-card', 'route' => 'admin.pembayaran'],
                        ['name' => 'Informasi', 'icon' => 'fa-circle-info', 'route' => 'admin.informasi'],
                        ['name' => 'Kelas', 'icon' => 'fa-chalkboard', 'route' => 'admin.kelas.index'],
                        // Tambahkan menu untuk Pesan Kontak
                        [
                            'name' => 'Pesan Masuk',
                            'icon' => 'fa-envelope',
                            'route' => 'admin.pesan.index',
                            'badge' => $unreadCount ?? 0,
                        ],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    @php
                        // Memastikan route exist sebelum memanggil route()
                        $href = Route::has($menu['route']) ? route($menu['route']) : '#';
                        $isActive = request()->routeIs($menu['route'] . '*');
                    @endphp
                    <a href="{{ $href }}"
                        class="flex items-center justify-between gap-3 py-3 px-4 rounded-lg text-base font-medium transition-all duration-200
                       {{ $isActive ? 'bg-yellow-400 text-black shadow-md' : 'bg-[#f4f4f4]/20 hover:bg-[#f4f4f4]/30 text-white' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid {{ $menu['icon'] }} text-[16px]"></i>
                            <span>{{ $menu['name'] }}</span>
                        </span>

                        {{-- Badge untuk Sidebar (Hanya tampil jika ada pesan belum dibaca) --}}
                        @if (isset($menu['badge']) && $menu['badge'] > 0)
                            <span
                                class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ $menu['badge'] > 9 ? '9+' : $menu['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="p-5 border-t border-[#103052] flex-shrink-0">
            <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 py-3 px-4 rounded-lg text-base w-full bg-[#f4f4f4]/20 hover:bg-[#f4f4f4]/30 transition-all duration-200">
                    <i class="fa-solid fa-right-from-bracket text-[16px]"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col ml-64">

        <header class="main-header flex items-center justify-between bg-white p-4 shadow-md">
            <h1 class="text-xl font-semibold text-gray-800">@yield('page', 'Overview')</h1>

            <div class="flex items-center gap-6">

                {{-- WIDGET TANGGAL & JAM --}}
                <div id="datetime-widget" class="text-sm text-gray-600 font-medium hidden sm:flex items-center gap-2">
                    <i class="far fa-calendar-alt"></i>
                    <span id="current-datetime">Loading...</span>
                </div>

                <div class="relative flex items-center gap-6 border-l pl-4">

                    {{-- START: NOTIFIKASI PESAN MASUK (LONCENG) --}}
                    @if (Auth::user()->role === 'admin')
                        <div class="relative">
                            <button id="notification-menu-button" type="button"
                                class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full transition duration-150 ease-in-out"
                                aria-expanded="false" aria-haspopup="true">
                                <i class="fa-solid fa-bell text-xl"></i>
                            </button>

                            {{-- Badge Notifikasi --}}
                            @if (isset($unreadCount) && $unreadCount > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif

                            {{-- Dropdown Notifikasi --}}
                            <div id="notification-menu-dropdown"
                                class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                role="menu" aria-orientation="vertical" aria-labelledby="notification-menu-button"
                                tabindex="-1">

                                <div class="px-4 py-3 border-b text-center">
                                    <p class="text-sm font-bold text-gray-900">Pesan Baru ({{ $unreadCount ?? 0 }})</p>
                                </div>

                                @if (isset($latestMessages) && $latestMessages->isEmpty())
                                    <div class="p-4 text-center text-sm text-gray-500">
                                        Tidak ada pesan baru.
                                    </div>
                                @else
                                    <div class="max-h-60 overflow-y-auto">
                                        @foreach ($latestMessages as $message)
                                            <a href="{{ route('admin.pesan.show', $message->id) }}"
                                                class="flex items-start px-4 py-3 hover:bg-indigo-50 border-b last:border-b-0 transition duration-150 ease-in-out">
                                                <div class="flex-shrink-0 pt-1">
                                                    <i class="fa-solid fa-envelope text-indigo-500 text-sm"></i>
                                                </div>
                                                <div class="ms-3 overflow-hidden">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">Dari:
                                                        {{ $message->contactName }}</p>
                                                    {{-- Untuk menggunakan Str::limit, Anda harus menambahkan: @inject('str', 'Illuminate\Support\Str') di atas --}}
                                                    <p class="text-xs text-gray-600 truncate">
                                                        {{ Illuminate\Support\Str::limit($message->contactComment, 40) }}
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="p-2 border-t text-center">
                                        <a href="{{ route('admin.pesan.index') }}"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Lihat Semua Pesan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    {{-- END: NOTIFIKASI PESAN MASUK (LONCENG) --}}

                    {{-- Nama User --}}
                    <span class="text-sm text-gray-700 font-medium">
                        {{ Auth::user()->name ?? 'User' }}
                    </span>

                    {{-- Profile Dropdown Button --}}
                    <button id="profile-menu-button" type="button"
                        class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-[#164370]"
                        aria-expanded="false" aria-haspopup="true">
                        <img class="w-9 h-9 rounded-full border object-cover"
                            src="{{ Auth::user()->profile_image ? asset('uploads/profile_pictures/' . Auth::user()->profile_image) : asset('https://i.pravatar.cc/40') }}"
                            alt="Profile">
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="profile-menu-dropdown"
                        class="hidden origin-top-right absolute right-0 top-10 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                        role="menu" aria-orientation="vertical" aria-labelledby="profile-menu-button" tabindex="-1">

                        <div class="px-4 py-2 text-xs text-gray-500 border-b mb-1">
                            Role: Admin
                        </div>

                        <a href="{{ route('admin.profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                            tabindex="-1" id="user-menu-item-0">
                            <i class="fas fa-user-cog mr-2"></i> Pengaturan Profil
                        </a>

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t mt-1"
                            role="menuitem" tabindex="-1" id="user-menu-item-1">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <section class="p-6 flex-1 bg-gray-50 min-h-screen">
            @yield('content')
        </section>
    </main>
    {{-- resources/views/layouts/dashboard.blade.php (Di bagian bawah) --}}

    <script>
        // Konstanta waktu dalam milidetik (300000 ms = 5 menit)
        const AUTO_RELOAD_INTERVAL = 300000;

        document.addEventListener('DOMContentLoaded', function() {

            // Cek apakah user adalah admin sebelum memulai reload otomatis
            if ('{{ Auth::user()->role }}' === 'admin') {
                console.log('Reload otomatis sistem diaktifkan setiap 5 menit.');

                setTimeout(function() {
                    // Gunakan location.reload(true) untuk memuat ulang dari server
                    // Ini akan memastikan data terbaru (termasuk notifikasi) dimuat
                    location.reload(true);
                }, AUTO_RELOAD_INTERVAL);
            }
        });

        // ... Script Jam dan Toggle Dropdown lainnya ...
    </script>

    {{-- Script untuk Jam dan Tanggal Dinamis --}}
    <script>
        function updateDateTime() {
            const now = new Date();
            const optionsDate = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const optionsTime = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            const dateStr = now.toLocaleDateString('id-ID', optionsDate);
            const timeStr = now.toLocaleTimeString('id-ID', optionsTime);

            document.getElementById('current-datetime').textContent = `${dateStr}, ${timeStr} WIB`;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

    {{-- Script untuk Toggle Dropdown Profil dan Notifikasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Profil
            const profileButton = document.getElementById('profile-menu-button');
            const profileDropdown = document.getElementById('profile-menu-dropdown');

            if (profileButton && profileDropdown) {
                profileButton.addEventListener('click', (event) => {
                    event.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', (event) => {
                    if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }

            // Toggle Notifikasi (BARU)
            const notifButton = document.getElementById('notification-menu-button');
            const notifDropdown = document.getElementById('notification-menu-dropdown');

            if (notifButton && notifDropdown) {
                notifButton.addEventListener('click', (event) => {
                    event.stopPropagation();
                    notifDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (event) => {
                    if (!notifButton.contains(event.target) && !notifDropdown.contains(event.target)) {
                        notifDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>

</html>
