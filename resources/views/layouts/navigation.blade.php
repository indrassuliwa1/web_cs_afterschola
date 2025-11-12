<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a
                        href="{{ route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'supervisor.dashboard') }}">
                        {{-- Ganti dengan component logo yang sesuai, misalnya <img src="..."> --}}
                        <x-application-logo class="block h-9 w-auto text-gray-800" />
                        </a>
                    </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                            </x-nav-link>
                        {{-- Menggunakan route kelas.index yang ada di sidebar Anda --}}
                        <x-nav-link :href="route('admin.kelas.index')" :active="request()->routeIs('admin.kelas.index')">
                            {{ __('Kelas') }}
                            </x-nav-link>
                        <x-nav-link :href="route('admin.pembayaran')" :active="request()->routeIs('admin.pembayaran')">
                            {{ __('Pembayaran') }}
                            </x-nav-link>
                        <x-nav-link :href="route('admin.kontrak')" :active="request()->routeIs('admin.kontrak')">
                            {{ __('Kontrak') }}
                            </x-nav-link>
                        <x-nav-link :href="route('admin.informasi')" :active="request()->routeIs('admin.informasi')">
                            {{ __('Informasi') }}
                            </x-nav-link>
                    @elseif(Auth::user()->role === 'supervisor')
                        <x-nav-link :href="route('supervisor.dashboard')" :active="request()->routeIs('supervisor.dashboard')">
                            {{ __('Dashboard') }}
                            </x-nav-link>
                    @endif
                    </div>
                </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                                        clip-rule="evenodd" />
                                    
                                </svg>
                                </div>
                            </button>
                        </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                            </x-dropdown-link>

                        <form method="POST"
                            action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
</nav>
