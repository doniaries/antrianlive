<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div>
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
                <div class="flex flex-col">
                    <!-- Chart Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Pengunjung Tiket</h2>
                        
                        <!-- Chart Type Selector -->
                        <div class="flex flex-wrap gap-2">
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

                    <!-- Legend Indicator -->
                    <div class="flex flex-wrap justify-center sm:justify-start items-center gap-x-4 mb-3 sm:mb-4">
                        <div class="inline-flex items-center">
                            <span class="size-2.5 inline-block bg-blue-600 rounded-sm me-2"></span>
                            <span class="text-[13px] text-gray-600 dark:text-neutral-400">
                                Menunggu
                            </span>
                        </div>
                        <div class="inline-flex items-center">
                            <span class="size-2.5 inline-block bg-yellow-500 rounded-sm me-2"></span>
                            <span class="text-[13px] text-gray-600 dark:text-neutral-400">
                                Dipanggil
                            </span>
                        </div>
                        <div class="inline-flex items-center">
                            <span class="size-2.5 inline-block bg-green-500 rounded-sm me-2"></span>
                            <span class="text-[13px] text-gray-600 dark:text-neutral-400">
                                Selesai
                            </span>
                        </div>
                    </div>
                    <!-- End Legend Indicator -->

                    <!-- Chart Container -->
                    <div id="ticket-chart" class="h-80 w-full"></div>
                </div>

                <script>
                document.addEventListener('livewire:load', function () {
                    const chartData = @json($chartData);
                    const chartType = '{{ $chartType }}';
                    
                    // Initialize chart
                    let chart = new ApexCharts(document.querySelector("#ticket-chart"), getChartOptions(chartType, chartData));
                    chart.render();
                    
                    // Update chart when Livewire updates the data
                    Livewire.on('chartUpdated', () => {
                        const updatedData = @this.chartData;
                        const updatedType = @this.chartType;
                        
                        chart.updateOptions(getChartOptions(updatedType, updatedData));
                    });

                    // Handle window resize
                    window.addEventListener('resize', function() {
                        chart.updateOptions({
                            chart: {
                                width: '100%'
                            }
                        });
                    });
                    
                    function getChartOptions(type, data) {
                        let series = [];
                        let categories = [];
                        
                        if (type === 'services') {
                            // Prepare data for services chart
                            categories = data.labels || [];
                            series = [{
                                name: 'Jumlah Tiket',
                                data: data.data || []
                            }];
                        } else {
                            // Prepare data for time-based charts
                            categories = data.labels || [];
                            
                            if (data.datasets && data.datasets.length > 0) {
                                series = data.datasets.map(dataset => ({
                                    name: dataset.label,
                                    data: dataset.data || []
                                }));
                            } else {
                                series = [{
                                    name: 'Jumlah Tiket',
                                    data: data.data || []
                                }];
                            }
                        }

                        return {
                            chart: {
                                height: '100%',
                                type: 'line',
                                fontFamily: 'Inter, ui-sans-serif',
                                toolbar: {
                                    show: true,
                                    tools: {
                                        download: true,
                                        selection: true,
                                        zoom: false,
                                        zoomin: false,
                                        zoomout: false,
                                        pan: false,
                                        reset: true
                                    }
                                },
                                zoom: {
                                    enabled: false
                                },
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 800,
                                    animateGradually: {
                                        enabled: true,
                                        delay: 150
                                    },
                                    dynamicAnimation: {
                                        enabled: true,
                                        speed: 350
                                    }
                                }
                            },
                            series: series,
                            stroke: {
                                curve: 'smooth',
                                width: [3, 3, 3],
                                dashArray: [0, 0, 0]
                            },
                            colors: ['#2563EB', '#eab308', '#22c55e'],
                            dataLabels: {
                                enabled: false
                            },
                            markers: {
                                size: 4,
                                hover: {
                                    size: 6
                                }
                            },
                            xaxis: {
                                categories: categories,
                                labels: {
                                    style: {
                                        colors: '#9ca3af',
                                        fontSize: '12px',
                                        fontFamily: 'Inter, ui-sans-serif',
                                        fontWeight: 400
                                    },
                                    formatter: function(value) {
                                        // Format date labels if needed
                                        if (type === 'daily' || type === 'weekly' || type === 'monthly') {
                                            return value.split(' ')[0];
                                        }
                                        return value;
                                    }
                                },
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: '#9ca3af',
                                        fontSize: '12px',
                                        fontFamily: 'Inter, ui-sans-serif',
                                        fontWeight: 400
                                    },
                                    formatter: function(value) {
                                        return Math.floor(value) === value ? value : '';
                                    }
                                },
                                forceNiceScale: true,
                                min: 0,
                                tickAmount: 5
                            },
                            grid: {
                                borderColor: '#e5e7eb',
                                strokeDashArray: 4,
                                padding: {
                                    top: 10,
                                    right: 0,
                                    bottom: 0,
                                    left: 10
                                }
                            },
                            tooltip: {
                                enabled: true,
                                shared: true,
                                intersect: false,
                                theme: 'light',
                                style: {
                                    fontSize: '12px',
                                    fontFamily: 'Inter, ui-sans-serif'
                                },
                                y: {
                                    formatter: function(value) {
                                        return value + ' tiket';
                                    }
                                }
                            },
                            legend: {
                                show: false
                            }
                        };
                    }
                });
                </script>
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

            <script>
                // Initialize Preline charts
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize charts
                    if (window.HSStaticMethods) {
                        HSStaticMethods.autoInit();
                    }
                    
                    // Listen for Livewire updates
                    Livewire.on('chartDataUpdated', () => {
                        console.log('Livewire updated, refreshing chart...');
                        if (window.HSStaticMethods) {
                            HSStaticMethods.autoInit();
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>