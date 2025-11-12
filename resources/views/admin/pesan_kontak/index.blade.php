@extends('layouts.dashboard')

@section('title', 'Pesan Masuk')
@section('page', 'Daftar Pesan Kontak')

@section('content')
    <div class="container mx-auto p-4 min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-envelope-open-text text-blue-600 mr-3"></i> Pesan Masuk
            </h1>

            {{-- Statistik Pesan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Total Pesan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pesan->total() ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Belum Dibaca</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $unreadCount ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Sudah Dibaca</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pesan->total() - ($unreadCount ?? 0) }}</p>
                </div>
            </div>

            {{-- Tabel Data Pesan --}}
            <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Riwayat Pesan Terbaru</h3>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subjek</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ringkasan Pesan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl
                                Kirim</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pesan as $item)
                            <tr
                                class="{{ $item->is_read ? 'hover:bg-gray-50' : 'bg-yellow-50 hover:bg-yellow-100 font-semibold' }}">

                                {{-- Status --}}
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                    <span title="{{ $item->is_read ? 'Sudah Dibaca' : 'Belum Dibaca' }}"
                                        class="{{ $item->is_read ? 'text-green-500' : 'text-red-500' }}">
                                        <i
                                            class="fas {{ $item->is_read ? 'fa-envelope-open' : 'fa-envelope' }} text-lg"></i>
                                    </span>
                                </td>

                                {{-- Nama --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->contactName }}
                                    <span class="block text-xs text-gray-500 font-normal">{{ $item->contactEmail }}</span>
                                </td>

                                {{-- Subjek --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ Illuminate\Support\Str::limit($item->contactSubject, 30) }}
                                </td>

                                {{-- Ringkasan Pesan --}}
                                <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ Illuminate\Support\Str::limit($item->contactComment, 50) }}
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-3">
                                        {{-- Tombol Lihat Detail --}}
                                        <a href="{{ route('admin.pesan.show', $item->id) }}"
                                            title="Lihat Detail Pesan dan Analisis Sentimen"
                                            class="text-blue-600 hover:text-blue-800 transition duration-150">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.pesan.destroy', $item->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Hapus pesan dari {{ $item->contactName }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Pesan"
                                                class="text-red-600 hover:text-red-800 transition duration-150">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500 text-base">
                                    <i class="fas fa-inbox mr-2"></i> Tidak ada pesan kontak masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $pesan->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
