@extends('layouts.dashboard')

{{-- CDN Chart.js di sini --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto">

            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-credit-card text-blue-600 mr-3"></i> Data Pembayaran
            </h1>

            {{-- Form Search & Tombol Tambah --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-3">
                {{-- Form Search --}}
                <form method="GET" action="{{ route('admin.pembayaran') }}" class="flex gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama pendaftar atau status..."
                        class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <button type="submit"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg transition duration-150">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                {{-- Tombol Aksi Kanan --}}
                <div class="flex items-center space-x-3">
                    {{-- Tombol Cetak Data --}}
                    <a href="{{ route('admin.pembayaran.print') }}" target="_blank"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                        <i class="fas fa-print"></i>
                        <span>Cetak Data</span>
                    </a>

                    {{-- Tambah Kontrak --}}
                    <a href="{{ route('admin.pembayaran.create') }}"
                        class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                        <i class="fas fa-plus"></i>
                        <span>Tambah</span>
                    </a>
                </div>
            </div>

            {{-- Statistik Pembayaran (Sudah Menggunakan Hitungan Kontrak Lunas/Pending) --}}
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

            {{-- BAGIAN BARU: GRAFIK & NOTIFIKASI BERSEBELAHAN --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                {{-- Kolom 1: GRAFIK TREN PEMASUKAN (Compact) --}}
                <div class="bg-white p-4 rounded-xl shadow-lg">
                    <h2 class="text-lg font-bold text-gray-800 flex justify-between items-center mb-2">
                        <span class="flex items-center text-sm">
                            <i class="fas fa-chart-line mr-2 text-blue-600"></i> Tren Pemasukan
                        </span>

                        {{-- Tombol Filter Periode --}}
                        <div id="payment-period-filters" class="flex space-x-1">
                            <button data-period="weekly"
                                class="payment-filter-btn px-2 py-1 text-xs rounded transition duration-150">Mgg</button>
                            <button data-period="monthly"
                                class="payment-filter-btn px-2 py-1 text-xs rounded transition duration-150">Bln</button>
                            <button data-period="yearly"
                                class="payment-filter-btn px-2 py-1 text-xs rounded transition duration-150">Thn</button>
                        </div>
                    </h2>

                    <div class="relative h-48">
                        {{-- Canvas Chart.js (Lebih kecil) --}}
                        <canvas id="paymentFinancialChart"></canvas>

                        {{-- Loading Indicator --}}
                        <div id="payment-chart-loading"
                            class="absolute inset-0 bg-white/70 flex items-center justify-center hidden rounded-xl">
                            <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2 text-center">Data tren pemasukan (Rp).</p>
                </div>

                {{-- Kolom 2: NOTIFIKASI KONTRAK BELUM LUNAS --}}
                @if ($unpaidContracts->count() > 0)
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-xl shadow-md">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                            <h3 class="font-bold text-base">Peringatan: Belum Lunas ({{ $unpaidContracts->count() }})</h3>
                        </div>
                        <p class="text-xs mb-3">Kontrak berikut belum melunasi total tagihan:</p>

                        <div class="space-y-2 max-h-36 overflow-y-auto pr-2">
                            @foreach ($unpaidContracts as $kontrak)
                                <div
                                    class="flex justify-between items-center bg-white p-2 rounded-lg border border-red-200">
                                    <div class="text-xs">
                                        <span
                                            class="font-semibold text-gray-800">{{ $kontrak->pendaftar->nama ?? 'N/A' }}</span>
                                        <span class="block text-[10px] text-gray-600">Sisa: Rp
                                            {{ number_format($kontrak->sisa_tagihan, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('admin.pembayaran.create', ['kontrak_id' => $kontrak->id]) }}"
                                        class="text-white bg-red-500 hover:bg-red-600 px-2 py-1 rounded-full text-[10px] transition duration-150">
                                        Bayar
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded-xl shadow-md flex items-center justify-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <h3 class="font-bold text-base">Semua Kontrak Sudah Lunas!</h3>
                    </div>
                @endif
            </div>

            {{-- Tabel Data Pembayaran (Agregasi Kontrak) --}}
            <div class="bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Riwayat Transaksi Pembayaran (Agregat per Kontrak)</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendaftar</th>

                            {{-- ✅ KOLOM ML RISK --}}
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                RISIKO</th>

                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bayar
                                Masuk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl
                                Akhir Bayar</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pembayaran as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}</td>
                                {{-- Menggabungkan Kontrak ID ke Nama Pendaftar --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $item->pendaftar->nama ?? 'N/A' }}
                                </td>

                                {{-- ✅ DATA PREDISI RISIKO ML --}}
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                    @php
                                        $riskClass = $item->risk_class ?? 'N/A';
                                        $riskScore = $item->risk_score ?? null;
                                        $riskColor = match ($riskClass) {
                                            'Tinggi' => 'bg-red-100 text-red-800',
                                            'Rendah' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $riskColor }}"
                                        title="Skor: {{ $riskScore ? number_format($riskScore, 2) : 'N/A' }}">
                                        {{ $riskClass }}
                                    </span>
                                </td>

                                {{-- Menampilkan Total Bayar Masuk (Agregat) --}}
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

                                {{-- Kolom Aksi (DIKEMBALIKAN KE Ikon Standard Kontrak) --}}
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-3">
                                        {{-- FIX: Tombol Detail merujuk ke ID Pembayaran Terakhir --}}
                                        @php
                                            // Ambil ID Pembayaran Terakhir (MAX ID) dari koleksi pembayaran Kontrak ini
                                            $lastPaymentId = $item->pembayaran->max('id');
                                        @endphp

                                        @if ($lastPaymentId)
                                            <a href="{{ route('admin.pembayaran.show', $lastPaymentId) }}"
                                                title="Lihat Detail Transaksi Terakhir"
                                                class="text-blue-600 hover:text-blue-800 transition duration-150">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>
                                        @else
                                            <span title="Belum ada transaksi" class="text-gray-400 cursor-not-allowed">
                                                <i class="fas fa-eye text-lg"></i>
                                            </span>
                                        @endif

                                        {{-- Tombol Edit Kontrak --}}
                                        <a href="{{ route('admin.kontrak.edit', $item->id) }}" title="Edit Detail Kontrak"
                                            class="text-yellow-600 hover:text-yellow-800 transition duration-150">
                                            <i class="fas fa-edit text-lg"></i>
                                        </a>

                                        {{-- Tombol Hapus Kontrak --}}
                                        <form action="{{ route('admin.kontrak.destroy', $item->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus Kontrak (#{{ $item->id }}) ini beserta semua data pembayarannya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Kontrak"
                                                class="text-red-600 hover:text-red-800 transition duration-150">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 text-base">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('paymentFinancialChart').getContext('2d');
            const loadingElement = document.getElementById('payment-chart-loading');
            const filterButtons = document.querySelectorAll('.payment-filter-btn');
            let financialChart;
            let currentPeriod = 'monthly'; // Mengubah default dari 'weekly' ke 'monthly' agar lebih stabil

            // --- FUNGSI FORMATTING ---
            function formatWeeklyLabel(dateString) {
                const date = new Date(dateString + 'T00:00:00');
                const options = {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short'
                };
                return date.toLocaleDateString('id-ID', options);
            }

            /**
             * Mengambil data chart dari backend melalui AJAX.
             */
            function fetchChartData(period) {
                loadingElement.classList.remove('hidden');

                const url = '{{ route('admin.pembayaran.chartData') }}' + '?period=' + period;

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengambil data chart. Status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        updateChart(data);
                        loadingElement.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                        loadingElement.classList.add('hidden');
                    });
            }

            /**
             * Memperbarui atau menginisialisasi Chart.js
             */
            function updateChart(data) {
                if (financialChart) {
                    financialChart.destroy();
                }

                const period = data.period || currentPeriod;
                const labels = period === 'weekly' ?
                    data.labels.map(formatWeeklyLabel) :
                    data.labels;

                financialChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pemasukan (Rp)',
                            data: data.datasets[0].data,
                            backgroundColor: 'rgba(59, 130, 246, 0.4)',
                            borderColor: 'rgba(37, 99, 235, 1)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: 'white',
                            pointBorderColor: 'rgba(37, 99, 235, 1)',
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 7
                                },
                                title: {
                                    display: false
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: false
                                },
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000) return (value / 1000000).toFixed(1)
                                            .replace(/\.0$/, '') + ' Jt';
                                        if (value >= 1000) return (value / 1000).toFixed(0) + ' Rb';
                                        return value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            /**
             * Mengatur status aktif pada tombol filter dan memuat data baru.
             */
            function setPeriod(newPeriod) {
                currentPeriod = newPeriod;

                filterButtons.forEach(btn => {
                    if (btn.getAttribute('data-period') === newPeriod) {
                        // Aktif
                        btn.classList.add('bg-blue-600', 'text-white');
                        btn.classList.remove('bg-gray-200', 'text-gray-700');
                    } else {
                        // Non-Aktif
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    }
                });

                fetchChartData(currentPeriod);
            }

            // Event listeners untuk tombol filter
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const period = this.getAttribute('data-period');
                    setPeriod(period);
                });
            });

            // Muat data awal saat halaman dimuat (default: monthly)
            setPeriod('monthly');
        });
    </script>
@endsection
