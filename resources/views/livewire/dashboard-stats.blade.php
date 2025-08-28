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
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-colors duration-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistics['total_today'] }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Hari Ini</div>
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
        </div>

        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Antrian</h2>
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
                </div>
            </div>
            
            <div class="h-64">
                <canvas id="queueChart" wire:ignore></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h2>
            </div>
            <div class="p-6">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Dashboard ini akan diperbarui otomatis setiap 5 detik
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chart;
    
    function initChart(data) {
        const ctx = document.getElementById('queueChart').getContext('2d');
        
        if (chart) {
            chart.destroy();
        }
        
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    label: 'Jumlah Antrian',
                    data: data.map(item => item.value),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Initialize chart when component loads
    document.addEventListener('livewire:init', () => {
        initChart(@json($chartData));
    });

    // Update chart when data changes
    document.addEventListener('livewire:updated', () => {
        initChart(@json($chartData));
    });
</script>
@endpush