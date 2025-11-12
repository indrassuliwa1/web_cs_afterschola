@extends('layouts.dashboard')

@section('title', 'Daftar Kontrak')
@section('page', 'Monitoring Kontrak')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-file-contract text-blue-600 mr-3"></i> Data Kontrak (Read-Only)
            </h1>

            {{-- Form Search (Tetap ada) --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-3">
                <form method="GET" action="{{ route('supervisor.kontrak') }}" class="flex gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kontrak..."
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <button type="submit"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition duration-150">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                {{-- Tombol Aksi Kanan HANYA CETAK --}}
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.kontrak.printAll') }}" target="_blank"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                        <i class="fas fa-print"></i>
                        <span>Cetak Semua</span>
                    </a>
                </div>
            </div>

            {{-- Tabel Data Kontrak --}}
            <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Kontrak</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendaftar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kontrak as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->nama_kontrak }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->pendaftar->nama ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass =
                                            $item->status === 'aktif'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800';
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                {{-- Kolom Aksi HANYA VIEW --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('admin.kontrak.show', $item->id) }}" title="Lihat Detail Kontrak"
                                            class="text-blue-600 hover:text-blue-800 transition duration-150">
                                            <i class="fas fa-eye text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500 text-base">
                                    <i class="fas fa-box-open mr-2"></i> Tidak ada data kontrak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $kontrak->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
