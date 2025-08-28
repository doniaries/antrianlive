<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Antrian</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sistem Antrian Pelayanan</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ now()->format('d/m/Y') }}</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white" x-data="{ time: '' }"
                        x-init="() => {
                            const updateTime = () => {
                                const now = new Date();
                                const hours = String(now.getHours()).padStart(2, '0');
                                const minutes = String(now.getMinutes()).padStart(2, '0');
                                const seconds = String(now.getSeconds()).padStart(2, '0');
                                time = `${hours}:${minutes}:${seconds}`;
                            };
                            updateTime();
                            setInterval(updateTime, 1000);
                        }" x-text="time"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" wire:poll.{{ $this->refreshRate }}ms="loadData">
            <!-- Enhanced Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['total_today'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Tiket</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statistics['waiting'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Menunggu</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $statistics['called'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Dipanggil</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $statistics['finished'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Selesai</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $statistics['skipped'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Dilewati</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $statistics['total_services'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Layanan Aktif</div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $statistics['avg_wait_time'] }}<span class="text-sm">min</span></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Tunggu</div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Main Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Pengunjung Tiket</h2>
                    <div class="flex space-x-2">
                        <button wire:click="setChartType('daily')" 
                                class="px-3 py-1 text-sm rounded {{ $chartType === 'daily' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Harian
                        </button>
                        <button wire:click="setChartType('weekly')" 
                                class="px-3 py-1 text-sm rounded {{ $chartType === 'weekly' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Mingguan
                        </button>
                        <button wire:click="setChartType('monthly')" 
                                class="px-3 py-1 text-sm rounded {{ $chartType === 'monthly' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Bulanan
                        </button>
                        <button wire:click="setChartType('services')" 
                                class="px-3 py-1 text-sm rounded {{ $chartType === 'services' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Layanan
                        </button>
                    </div>
                </div>
                
                <div class="h-80">
                    <canvas id="queueChart" wire:ignore style="min-height: 300px; width: 100% !important; height: auto !important;"></canvas>
                </div>
            </div>

            <!-- Peak Hours -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Jam Sibuk</h2>
                <div class="space-y-3">
                    @forelse($peakHours as $peak)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $peak['hour'] }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $peak['count'] }} tiket</span>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 dark:text-gray-400">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Service Statistics and Recent Tickets -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Service Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik per Layanan</h2>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Layanan</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Menunggu</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($serviceStats as $service)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $service['name'] }}</td>
                                        <td class="px-4 py-2 text-center text-sm text-gray-900 dark:text-white">{{ $service['total_today'] }}</td>
                                        <td class="px-4 py-2 text-center text-sm">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                {{ $service['waiting'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center text-sm">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $service['finished'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data layanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tiket Terbaru</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @forelse($recentTickets as $ticket)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $ticket->service->name ?? 'N/A' }} - {{ $ticket->nomor_antrian }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i:s') }} - 
                                        {{ $ticket->counter->name ?? 'Belum ditentukan' }}
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $statusColors = [
                                            'waiting' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                            'called' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                            'finished' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                            'skipped' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">Belum ada tiket</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chart = null;

    function initializeChart() {
        const canvas = document.getElementById('queueChart');
        if (!canvas) {
            console.error('Canvas element not found');
            return;
        }
        
        const ctx = canvas.getContext('2d');
        const chartType = @json($chartType);
        const chartData = @json($chartData);
        
        console.log('Chart Type:', chartType);
        console.log('Chart Data:', chartData);
        
        // Destroy existing chart if it exists
        if (chart) {
            chart.destroy();
            chart = null;
        }
        
        // Check if we have data
        if (!chartData || !chartData.labels || chartData.labels.length === 0 || !chartData.data || chartData.data.length === 0) {
            console.warn('No chart data available');
            // Clear canvas and show message
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.font = '16px Arial';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#9ca3af' : '#6b7280';
            ctx.textAlign = 'center';
            ctx.fillText('Memuat data...', canvas.width/2, canvas.height/2);
            return;
        }
        
        // Configure chart based on type
        const isDark = document.body.classList.contains('dark');
        
        let chartConfig = {
            type: chartType === 'services' ? 'bar' : 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: chartData.data,
                    borderColor: '#3b82f6',
                    backgroundColor: chartType === 'services' ? 'rgba(59, 130, 246, 0.8)' : 'rgba(59, 130, 246, 0.2)',
                    borderWidth: 2,
                    fill: chartType === 'services' ? false : true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDark ? '#374151' : '#e5e7eb',
                            borderColor: isDark ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            borderColor: isDark ? '#374151' : '#e5e7eb'
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 750,
                    easing: 'easeInOutQuart'
                }
            }
        };
        
        // Configure datasets based on chart type
        if (chartType === 'services') {
            chartConfig.data.datasets = [{
                label: 'Tiket per Layanan',
                data: chartData.data,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 146, 60, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)',
                    'rgb(236, 72, 153)',
                    'rgb(34, 197, 94)',
                    'rgb(251, 146, 60)'
                ],
                borderWidth: 1
            }];
        } else {
            // Line chart for daily/weekly/monthly trends
            chartConfig.data.datasets = [{
                label: 'Total Tiket',
                data: chartData.data,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }];
        }
        
        chart = new Chart(ctx, chartConfig);
    }

    function updateChart() {
        const canvas = document.getElementById('queueChart');
        if (!canvas) {
            console.error('Canvas element not found during update');
            return;
        }
        
        console.log('Updating chart...');
        initializeChart();
    }

    // Handle dark mode changes
    window.addEventListener('themeChanged', () => {
        updateChart();
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        if (chart) {
            chart.resize();
        }
    });

    // Initialize chart when component loads
    document.addEventListener('livewire:initialized', () => {
        setTimeout(() => {
            initializeChart();
        }, 500);
    });

    // Update chart when Livewire updates
    document.addEventListener('livewire:updated', () => {
        setTimeout(() => {
            updateChart();
        }, 100);
    });

    // Fallback initialization
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof Livewire === 'undefined') {
            setTimeout(() => {
                initializeChart();
            }, 1000);
        }
    });

    // Handle dark mode changes
    function handleDarkModeChange() {
        if (chart) {
            const isDark = document.documentElement.classList.contains('dark');
            chart.options.plugins.legend.labels.color = isDark ? '#e5e7eb' : '#374151';
            chart.options.scales.y.ticks.color = isDark ? '#e5e7eb' : '#374151';
            chart.options.scales.x.ticks.color = isDark ? '#e5e7eb' : '#374151';
            chart.options.scales.y.grid.color = isDark ? '#374151' : '#e5e7eb';
            chart.options.scales.x.grid.color = isDark ? '#374151' : '#e5e7eb';
            chart.update('none');
        }
    }

    // Listen for dark mode changes
    const observer = new MutationObserver(handleDarkModeChange);
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
</script>
@endpush