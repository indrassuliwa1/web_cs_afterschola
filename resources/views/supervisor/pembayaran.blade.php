@extends('layouts.dashboard')

@section('title', 'Data Pembayaran')
@section('page', 'Monitoring Pembayaran')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-credit-card text-green-600 mr-3"></i> Data Pembayaran (Read-Only)
            </h1>

            {{-- Form Search & Tombol Cetak --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-3">
                <form method="GET" action="{{ route('supervisor.pembayaran') }}" class="flex gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pendaftar..."
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <button type="submit"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition duration-150">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.pembayaran.print') }}" target="_blank"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                        <i class="fas fa-print"></i>
                        <span>Cetak Data</span>
                    </a>
                </div>
            </div>

            {{-- Statistik Pembayaran --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="p-6 bg-white rounded-xl shadow-lg border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Total Pembayaran (Rp)</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-lg border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Pembayaran Lunas</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalLunas ?? 0 }} Kontrak</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-lg border-l-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPending ?? 0 }} Kontrak</p>
                </div>
            </div>

            {{-- Tabel Data Pembayaran --}}
            <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Riwayat Transaksi Pembayaran (Agregat per Kontrak)</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendaftar</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                Bayar Masuk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Agregat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl
                                Terakhir Bayar</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pembayaran as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->pendaftar->nama ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-bold">
                                    Rp {{ number_format($item->total_bayar_masuk, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $statusAgregat = strtolower($item->status_pembayaran_agregasi);
                                        $statusClass = [
                                            'lunas' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$statusAgregat] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($item->status_pembayaran_agregasi) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->tanggal_terakhir_bayar ? \Carbon\Carbon::parse($item->tanggal_terakhir_bayar)->format('d M Y') : 'Belum Bayar' }}
                                </td>

                                {{-- Kolom Aksi HANYA VIEW DETAIL --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-3">
                                        @php
                                            // Ambil ID Pembayaran Terakhir (MAX ID) dari koleksi pembayaran Kontrak ini
                                            $lastPaymentId = $item->pembayaran->max('id');
                                        @endphp

                                        @if ($lastPaymentId)
                                            <a href="{{ route('admin.pembayaran.show', $lastPaymentId) }}"
                                                title="Lihat Detail Transaksi"
                                                class="text-blue-600 hover:text-blue-800 transition duration-150">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>
                                        @else
                                            <span title="Belum ada transaksi" class="text-gray-400 cursor-not-allowed">
                                                <i class="fas fa-eye text-lg"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500 text-base">
                                    <i class="fas fa-box-open mr-2"></i> Tidak ada data kontrak yang memiliki riwayat
                                    pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $pembayaran->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
