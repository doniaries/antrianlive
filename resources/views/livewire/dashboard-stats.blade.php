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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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
                
                <div class="relative" style="min-height: 320px; height: 320px;">
                    <!-- Chart Container -->
                    <div class="w-full h-full">
                        <canvas id="queueChart" wire:ignore></canvas>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-white/70 dark:bg-gray-800/70 rounded-lg">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Memuat grafik...</p>
                        </div>
                    </div>
                    
                    <!-- Debug info (hidden by default, add 'block' class to show) -->
                    <div class="absolute top-2 left-2 bg-yellow-100/90 dark:bg-yellow-900/90 p-2 rounded text-xs z-10 hidden">
                        <div><strong>Chart Type:</strong> {{ $chartType }}</div>
                        <div><strong>Labels:</strong> {{ json_encode($chartData['labels'] ?? []) }}</div>
                        <div><strong>Data:</strong> {{ json_encode($chartData['data'] ?? []) }}</div>
                    </div>
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

function initChart() {
    const canvas = document.getElementById('queueChart');
    const loading = document.getElementById('chartLoading');
    
    // Check if required elements exist
    if (!canvas || typeof Chart === 'undefined') {
        if (loading) loading.style.display = 'none';
        return;
    }
    
    // Get data from Livewire
    const type = @json($chartType);
    const data = @json($chartData);
    
    // Clear previous chart if exists
    if (chart) {
        chart.destroy();
    }
    
    // Show loading state
    if (loading) {
        loading.style.display = 'flex';
    }
    
    // Set canvas dimensions
    const container = canvas.parentElement;
    canvas.width = container.offsetWidth;
    canvas.height = container.offsetHeight;
    
    // Get context
    const ctx = canvas.getContext('2d');
    
    // Handle no data case
    if (!data?.labels?.length) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.textAlign = 'center';
        ctx.font = '16px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
        ctx.fillStyle = window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9CA3AF' : '#6B7280';
        ctx.fillText('Tidak ada data yang tersedia', canvas.width / 2, canvas.height / 2);
        if (loading) loading.style.display = 'none';
        return;
    }
    
    // Chart configuration
    const chartType = type === 'services' ? 'bar' : 'line';
    const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    const chartOptions = {
        type: chartType,
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Jumlah Tiket',
                data: data.data,
                borderColor: '#3b82f6',
                backgroundColor: chartType === 'bar' 
                    ? 'rgba(59, 130, 246, 0.7)' 
                    : 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#3b82f6',
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                fill: chartType === 'line',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 10
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDarkMode ? '#1F2937' : '#ffffff',
                    titleColor: isDarkMode ? '#F3F4F6' : '#111827',
                    bodyColor: isDarkMode ? '#D1D5DB' : '#4B5563',
                    borderColor: isDarkMode ? '#374151' : '#E5E7EB',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return `Jumlah: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: isDarkMode ? '#9CA3AF' : '#6B7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        color: isDarkMode ? '#9CA3AF' : '#6B7280',
                        precision: 0
                    }
                }
            },
            animation: {
                duration: 800,
                easing: 'easeInOutQuart'
            },
            onHover: (event, chartElement) => {
                const canvas = event.native.target;
                canvas.style.cursor = chartElement[0] ? 'pointer' : 'default';
            }
        }
    };
    
    // Create new chart
    chart = new Chart(ctx, chartOptions);
    
    // Hide loading state
    if (loading) {
        loading.style.display = 'none';
    }
}

// Initialize chart when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initial chart load
    setTimeout(initChart, 300);
    
    // Handle window resize with debounce
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (chart) {
                chart.destroy();
                chart = null;
            }
            initChart();
        }, 250);
    });
    
    // Listen for Livewire component initialization
    Livewire.hook('component.initialized', component => {
        if (component.id === @this.__instance.id) {
            // Reinitialize chart when component is initialized
            setTimeout(initChart, 300);
        }
    });
});

// Listen for custom events from Livewire
window.addEventListener('chartDataUpdated', () => {
    if (chart) {
        chart.destroy();
        chart = null;
    }
    initChart();
});

// Handle Livewire component updates
Livewire.hook('morph.updated', ({ el, component }) => {
    if (component.id === @this.__instance.id) {
        setTimeout(() => {
            if (chart) {
                chart.destroy();
                chart = null;
            }
            initChart();
        }, 50);
    }
});
</script>
@endpush