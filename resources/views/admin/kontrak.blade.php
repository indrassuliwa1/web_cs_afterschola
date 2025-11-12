@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto">
        {{-- ===== HEADER ATAS ===== --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-4">
                {{-- Total Kontrak --}}
                <div class="bg-white border rounded-lg shadow p-4 flex items-center space-x-3">
                    <div>
                        <p class="text-sm text-gray-500">Total Kontrak</p>
                        <p class="text-2xl font-semibold">{{ number_format($totalKontrak) }}</p>
                    </div>
                    <i class="fas fa-file-contract text-2xl text-blue-700"></i>
                </div>

                {{-- Kontrak Aktif --}}
                <div class="bg-yellow-400 border rounded-lg shadow p-4 flex items-center space-x-3">
                    <div>
                        <p class="text-sm text-gray-800 font-semibold">Kontrak Aktif</p>
                        <p class="text-2xl font-semibold">{{ number_format($kontrakAktif) }}</p>
                    </div>
                    <i class="fas fa-check-circle text-2xl text-black"></i>
                </div>
            </div>

            {{-- Tombol Aksi Kanan --}}
            <div class="flex items-center space-x-3">
                {{-- Cetak Semua Kontrak --}}
                <a href="{{ route('admin.kontrak.printAll') }}" target="_blank"
                    class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                    <i class="fas fa-print"></i>
                    <span>Cetak Data</span>
                </a>

                {{-- Tambah Kontrak --}}
                <a href="{{ route('admin.kontrak.create') }}"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                    <i class="fas fa-plus"></i>
                    <span>Tambah</span>
                </a>
            </div>
        </div>

        {{-- ===== FILTER & PENCARIAN ===== --}}
        <form method="GET" action="{{ route('admin.kontrak') }}" class="mb-6">
            <div class="flex space-x-3">
                {{-- Input Pencarian --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kontrak..."
                    class="w-1/3 border rounded-lg p-2 focus:ring focus:ring-blue-200 focus:border-blue-400">

                {{-- Filter Status --}}
                <select name="status" class="border rounded-lg p-2 focus:ring focus:ring-blue-200 focus:border-blue-400">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                {{-- Tombol Cari --}}
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>

                {{-- Tombol Reset --}}
                @if (request('search') || request('status'))
                    <a href="{{ route('admin.kontrak') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- ===== TABEL DATA KONTRAK ===== --}}
        <h1 class="text-xl font-semibold mb-4">Data Kontrak</h1>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="table-auto w-full border">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2">Nama Kontrak</th>
                        <th class="px-4 py-2">Kelas</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Jumlah Peserta</th>
                        <th class="px-4 py-2">Mulai</th>
                        <th class="px-4 py-2">Selesai</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kontrak as $item)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="border px-4 py-2">{{ $item->nama_kontrak }}</td>
                            <td class="border px-4 py-2">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                @if ($item->status == 'aktif')
                                    <span
                                        class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm font-semibold">Aktif</span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $item->jumlah_peserta }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                            </td>
                            <td class="border px-4 py-2">
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</td>

                            {{-- 🛠️ KODE AKSI YANG TELAH DIUBAH AGAR SESUAI DENGAN GAMBAR --}}
                            <td class="border px-4 py-2 text-center">
                                <div class="flex items-center justify-center space-x-1">

                                    {{-- Tombol Detail / Lihat (Biru) --}}
                                    <a href="{{ route('admin.kontrak.show', $item->id) }}" title="Lihat Detail"
                                        class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md inline-flex items-center text-sm transition duration-150">
                                        <i class="fas fa-eye w-4 h-4"></i>
                                    </a>

                                    {{-- Tombol Edit (Kuning) --}}
                                    <a href="{{ route('admin.kontrak.edit', $item->id) }}" title="Edit Kontrak"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md inline-flex items-center text-sm transition duration-150">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </a>

                                    {{-- Tombol Hapus (Merah) --}}
                                    <form action="{{ route('admin.kontrak.destroy', $item->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus kontrak {{ $item->nama_kontrak }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Kontrak"
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-md inline-flex items-center text-sm transition duration-150">
                                            <i class="fas fa-trash w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            {{-- ---------------------------------------------------- --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-4">Tidak ada data kontrak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="mt-4">
            {{ $kontrak->appends(['search' => request('search'), 'status' => request('status')])->links() }}
        </div>
    </div>
@endsection
