@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page', 'Dashboard Sistem')

{{-- Inject Chart.js CDN di sini --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

@section('content')
    <div class="space-y-8">

        {{-- 1. Kartu Statistik Utama (Ukuran Besar) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Total Pemasukan --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border-b-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Pemasukan</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-money-bill-wave text-4xl text-yellow-500 opacity-60"></i>
                </div>
            </div>

            {{-- Total Kelas --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border-b-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Kelas</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $totalKelas ?? 0 }}
                        </p>
                    </div>
                    <i class="fas fa-chalkboard text-4xl text-green-500 opacity-60"></i>
                </div>
            </div>

            {{-- Total Pendaftar --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border-b-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Pendaftar</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $totalPendaftar ?? 0 }}
                        </p>
                    </div>
                    <i class="fas fa-users text-4xl text-blue-500 opacity-60"></i>
                </div>
            </div>

            {{-- Total Informasi --}}
            <div class="p-6 bg-white rounded-xl shadow-lg border-b-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Informasi</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">
                            {{ $totalInformasi ?? 0 }}
                        </p>
                    </div>
                    <i class="fas fa-bullhorn text-4xl text-purple-500 opacity-60"></i>
                </div>
            </div>
        </div>

        {{-- WIDGET PRIORITAS AKSI LAMA DIHAPUS DARI SINI --}}

        ---

        {{-- 2. GRAFIK UTAMA: TREN KEUANGAN & CHART SENTIMEN (Layout Awal) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom 1 (2/3): Tren Keuangan & Pendaftaran --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex justify-between items-center">
                    <span class="flex items-center">
                        <i class="fas fa-chart-line mr-2 text-primary-blue"></i> Tren Keuangan & Pendaftaran
                    </span>

                    <div class="flex items-center space-x-4">
                        {{-- Dropdown Filter Periode --}}
                        <div class="flex items-center space-x-2">
                            <label for="period-select" class="text-sm font-medium text-gray-600">Periode:</label>
                            <select id="period-select"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-2 transition duration-150">
                                <option value="monthly">Bulan</option>
                                <option value="weekly"selected>Minggu</option>
                                <option value="yearly">Tahun</option>
                            </select>
                        </div>

                        {{-- Dropdown Filter View --}}
                        <div class="border-l pl-4 flex items-center space-x-2">
                            <label for="view-select" class="text-sm font-medium text-gray-600">Tampilkan:</label>
                            <select id="view-select"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150">
                                <option value="both" selected>Kedua Data</option>
                                <option value="payments">Pemasukan Saja</option>
                                <option value="contracts">Pendaftar Saja</option>
                            </select>
                        </div>
                    </div>
                </h2>

                <div class="relative h-96">
                    <canvas id="financialChart"></canvas>
                    <div id="chart-loading"
                        class="absolute inset-0 bg-white/70 flex items-center justify-center hidden rounded-lg">
                        <i class="fas fa-spinner fa-spin text-primary-blue text-4xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center">Data bersumber dari **Jumlah Bayar** (garis biru) dan
                    **Kontrak Baru** (garis oranye).</p>
            </div>

            {{-- Kolom 2 (1/3): Kepuasan Pengguna & Pendaftar Terbaru --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- ✅ CHART ANALISIS KEPUASAN PENGGUNA (SENTIMEN) --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-smile-beam mr-2 text-purple-600"></i> Kepuasan Pengguna
                    </h2>
                    <div style="height: 180px;">
                        <canvas id="sentimentChart"></canvas>
                    </div>
                    @php
                        $totalMessages = array_sum($sentimentData['counts']);
                        $positiveCountIndex = array_search('positive', $sentimentData['labels']);
                        $positiveCount =
                            $positiveCountIndex !== false ? $sentimentData['counts'][$positiveCountIndex] : 0;
                        $positivePercent = $totalMessages > 0 ? round(($positiveCount / $totalMessages) * 100) : 0;
                    @endphp
                    <p class="text-center text-sm text-gray-600 mt-3">
                        **{{ $positivePercent }}%** pesan memiliki sentimen Positif.
                    </p>
                </div>


                {{-- Pendaftar Terbaru --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-blue-600"></i> Pendaftar / Kontrak Terbaru
                    </h2>
                    <div class="h-[250px] overflow-y-auto pr-2">
                        @if (empty($pendaftarTerbaru) || $pendaftarTerbaru->isEmpty())
                            <p class="text-gray-500 py-4 text-center italic">Tidak ada pendaftar terbaru.</p>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach ($pendaftarTerbaru as $kontrak)
                                    <li class="py-3 flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $kontrak->pendaftar->nama ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $kontrak->pendaftar->email ?? ($kontrak->pendaftar->no_hp ?? 'No Contact') }}
                                            </p>
                                        </div>
                                        <span class="text-xs text-blue-600 font-medium whitespace-nowrap">
                                            {{ $kontrak->created_at->diffForHumans() }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        ---

        {{-- 3. Berita / Informasi Terbaru --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-newspaper mr-2 text-purple-600"></i> Berita & Informasi Terbaru
            </h2>
            <div class="bg-gray-50 rounded-lg p-4">
                @if (empty($informasiTerbaru) || $informasiTerbaru->isEmpty())
                    <p class="text-gray-500 italic py-4 text-center">Belum ada informasi terbaru yang diterbitkan.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($informasiTerbaru as $info)
                            <li class="py-3">
                                <a href="{{ route('admin.informasi.show', $info->id) }}"
                                    class="block hover:bg-gray-100 rounded p-1 -m-1 transition duration-150">
                                    <span class="font-medium text-gray-800 block">{{ $info->judul }}</span>
                                    <span class="text-sm text-gray-500 block mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($info->created_at)->format('d M Y') }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>

    {{-- ✅ FLOATING ACTION BUTTON DAN MODAL POPUP (BARU) --}}
    @php
        $totalActionItems = count($actionItems);
    @endphp

    @if ($totalActionItems > 0)
        <div class="fixed bottom-8 right-8 z-50">
            <button id="priority-btn"
                class="relative bg-red-600 hover:bg-red-700 text-white p-4 rounded-full shadow-2xl transition duration-300 transform hover:scale-110">
                <i class="fas fa-bolt text-2xl"></i>
                {{-- Badge Notifikasi Angka --}}
                <span
                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-800 rounded-full transform translate-x-1/4 -translate-y-1/4 ring-2 ring-white">
                    {{ $totalActionItems > 9 ? '9+' : $totalActionItems }}
                </span>
            </button>
        </div>

        {{-- MODAL POPUP PRIORITAS AKSI --}}
        <div id="priority-modal"
            class="hidden fixed inset-0 z-[60] flex items-start justify-center pt-20 bg-gray-900 bg-opacity-75 transition-opacity duration-300"
            onclick="closeModal(event)">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative animate-slide-in-up"
                onclick="event.stopPropagation()">

                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-xl font-extrabold text-red-700 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i> Prioritas Aksi Secepatnya!
                    </h3>
                    <button onclick="document.getElementById('priority-modal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach ($actionItems as $item)
                        @php
                            $icon = match ($item['type']) {
                                'Pesan Negatif' => 'fa-comment-slash',
                                'Kontrak Risiko' => 'fa-calendar-times',
                                default => 'fa-info-circle',
                            };
                            $borderColor = $item['urgency'] === 'Tinggi' ? 'border-red-600' : 'border-yellow-600';
                            $textColor = $item['urgency'] === 'Tinggi' ? 'text-red-700' : 'text-yellow-700';
                        @endphp
                        <a href="{{ $item['url'] }}"
                            class="block p-3 bg-white rounded-lg shadow hover:shadow-lg transition duration-200 border-l-4 {{ $borderColor }}">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm {{ $textColor }}">
                                    {{ $item['urgency'] }} - {{ $item['type'] }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $item['time_ago'] }}</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 mt-1">{{ $item['description'] }}</p>
                            <p class="text-xs text-blue-500 mt-1 flex items-center">
                                <i class="fas {{ $icon }} text-xs mr-1"></i> Klik untuk Tindak Lanjut
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


    {{-- Script Chart.js --}}
    <script>
        // --- Logika Modal ---
        function openModal() {
            document.getElementById('priority-modal').classList.remove('hidden');
        }

        function closeModal(event) {
            if (event.target.id === 'priority-modal') {
                document.getElementById('priority-modal').classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk tombol floating
            const priorityBtn = document.getElementById('priority-btn');
            if (priorityBtn) {
                priorityBtn.addEventListener('click', openModal);
            }

            // --- Logika Lainnya (Chart & Data) ---
            const ctx = document.getElementById('financialChart').getContext('2d');
            const loadingElement = document.getElementById('chart-loading');

            const periodSelect = document.getElementById('period-select');
            const viewSelect = document.getElementById('view-select');

            let financialChart;
            let chartRawData;
            let currentPeriod = 'monthly';
            let currentView = 'both';

            // --- DATA SENTIMEN DARI PHP ---
            const sentimentData = @json($sentimentData);
            // ------------------------------

            // --- FUNGSI FORMATTING ---
            function formatWeeklyLabel(dateString) {
                const date = new Date(dateString.replace(/-/g, '/'));
                const options = {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short'
                };
                return date.toLocaleDateString('id-ID', options);
            }

            /**
             * Mengambil data chart dari backend melalui AJAX.
             */
            function fetchChartData(period) {
                currentPeriod = period;
                loadingElement.classList.remove('hidden');

                const url = '{{ route('admin.dashboard.chartData') }}' + '?period=' + period;

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
                            throw new Error('Gagal mengambil data chart.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        chartRawData = data;
                        updateChart(data, period, currentView);
                        loadingElement.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                        loadingElement.classList.add('hidden');
                    });
            }

            /**
             * Mengontrol tampilan dataset berdasarkan view yang dipilih.
             */
            function updateChartView(view) {
                currentView = view;
                if (!financialChart || !chartRawData) return;

                const paymentsDatasetIndex = 0;
                const contractsDatasetIndex = 1;

                // Sembunyikan/Tampilkan dataset
                financialChart.data.datasets[paymentsDatasetIndex].hidden = (view === 'contracts');
                financialChart.data.datasets[contractsDatasetIndex].hidden = (view === 'payments');

                // Atur sumbu Y berdasarkan view
                const isPaymentsVisible = !financialChart.data.datasets[paymentsDatasetIndex].hidden;
                const isContractsVisible = !financialChart.data.datasets[contractsDatasetIndex].hidden;

                // Tampilkan Sumbu Y1 (Pemasukan) jika Pemasukan terlihat, atau jika KEDUANYA tidak terlihat (fall back)
                financialChart.options.scales.y1.display = isPaymentsVisible || !isContractsVisible;

                // Tampilkan Sumbu Y2 (Pendaftar) jika Pendaftar terlihat
                financialChart.options.scales.y2.display = isContractsVisible;

                // Khusus mode 'contracts' (Pendaftar saja), sumbu Y1 harus disembunyikan
                if (view === 'contracts' && isContractsVisible) {
                    financialChart.options.scales.y1.display = false;
                }

                financialChart.update();
            }


            /**
             * Memperbarui atau menginisialisasi Chart.js (Financial/Contracts Chart)
             */
            function updateChart(data, period, view) {
                if (financialChart) {
                    financialChart.destroy();
                }

                const labels = period === 'weekly' ?
                    data.labels.map(formatWeeklyLabel) :
                    data.labels;

                // Konfigurasi Chart.js
                financialChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: data.datasets.map((dataset, index) => ({
                            ...dataset,
                            pointBackgroundColor: dataset.borderColor,
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: dataset.borderColor,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            // Set visibility awal
                            hidden: (view === 'payments' && index === 1) || (view ===
                                'contracts' && index === 0),
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    boxWidth: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            if (context.dataset.yAxisID === 'y1') {
                                                label += 'Rp ' + context.parsed.y.toLocaleString(
                                                    'id-ID');
                                            } else {
                                                label += context.parsed.y.toLocaleString('id-ID');
                                            }
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
                                title: {
                                    display: true,
                                    text: period === 'weekly' ? 'Hari/Tanggal' : (period === 'monthly' ?
                                        'Bulan/Tahun' : 'Tahun')
                                },
                                ticks: {
                                    maxTicksLimit: 8
                                }
                            },
                            // SUMBU Y KIRI (Pemasukan)
                            y1: {
                                type: 'linear',
                                position: 'left',
                                beginAtZero: true,
                                display: view !== 'contracts',
                                title: {
                                    display: true,
                                    text: 'Pemasukan (Rp)',
                                    color: '#164370'
                                },
                                ticks: {
                                    color: '#164370',
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(1).replace(/\.0$/, '') +
                                                ' Jt';
                                        }
                                        if (value >= 1000) {
                                            return (value / 1000).toFixed(0) + ' Rb';
                                        }
                                        return value;
                                    }
                                }
                            },
                            // SUMBU Y KANAN (Pendaftar)
                            y2: {
                                type: 'linear',
                                position: 'right',
                                beginAtZero: true,
                                display: view !== 'payments',
                                grid: {
                                    drawOnChartArea: false,
                                },
                                title: {
                                    display: true,
                                    text: 'Pendaftar (Jumlah)',
                                    color: '#F59E0B'
                                },
                                ticks: {
                                    color: '#F59E0B',
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                // Panggil updateChartView di akhir untuk memastikan visibility sumbu Y sudah benar
                updateChartView(view);
            }

            // --- FUNGSI CHART SENTIMEN (DOUGHNUT) ---
            function initializeSentimentChart() {
                const sentCtx = document.getElementById('sentimentChart').getContext('2d');

                const labels = sentimentData.labels.map(label => {
                    return label.charAt(0).toUpperCase() + label.slice(1);
                });

                // Urutan warna: Hijau (Positif), Merah (Negatif), Abu-abu (Netral)
                const colors = {
                    'positive': '#10B981', // Emerald Green
                    'negative': '#F87171', // Red
                    'neutral': '#9CA3AF' // Gray
                };

                const sortedData = labels.map((label, index) => ({
                    label: label,
                    data: sentimentData.counts[index],
                    color: colors[label.toLowerCase()] || '#9CA3AF'
                })).sort((a, b) => {
                    // Sort untuk konsistensi (misal: Positive, Negative, Neutral)
                    const order = {
                        'Positive': 1,
                        'Negative': 2,
                        'Neutral': 3
                    };
                    return order[a.label] - order[b.label];
                });

                new Chart(sentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: sortedData.map(d => d.label),
                        datasets: [{
                            data: sortedData.map(d => d.data),
                            backgroundColor: sortedData.map(d => d.color),
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12
                                }
                            },
                            title: {
                                display: false
                            }
                        }
                    }
                });
            }
            // --- END FUNGSI CHART SENTIMEN ---


            // ===================================
            // EVENT LISTENERS 
            // ===================================

            // 1. Listener Dropdown Periode
            periodSelect.addEventListener('change', function() {
                fetchChartData(this.value);
            });

            // 2. Listener Dropdown View
            viewSelect.addEventListener('change', function() {
                updateChartView(this.value);
            });

            // ===================================
            // FUNGSI INITIATOR
            // ===================================

            function initDashboard() {
                currentPeriod = periodSelect.value;
                currentView = viewSelect.value;

                fetchChartData(currentPeriod);
                initializeSentimentChart(); // Inisialisasi Chart Sentimen
            }

            initDashboard();
        });
    </script>
@endsection
