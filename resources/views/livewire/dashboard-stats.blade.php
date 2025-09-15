<div wire:poll.3s="refreshDashboard">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Antrian Hari Ini -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Hari Ini</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalAntrianHariIni) }}</p>
                    </div>
                </div>
                <div class="text-xs text-green-600 dark:text-green-400 font-medium">
                    <span class="inline-flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Live
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Antrian Minggu Ini -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Minggu Ini</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($antrianMingguIni) }}</p>
                    </div>
                </div>
                <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                    7 hari
                </div>
            </div>
        </div>

        <!-- Total Antrian Bulan Ini -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Bulan Ini</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($antrianBulanIni) }}</p>
                    </div>
                </div>
                <div class="text-xs text-purple-600 dark:text-purple-400 font-medium">
                    {{ now()->format('M Y') }}
                </div>
            </div>
        </div>

        <!-- Total Antrian Tahun Ini -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun Ini</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($antrianTahunIni) }}</p>
                    </div>
                </div>
                <div class="text-xs text-orange-600 dark:text-orange-400 font-medium">
                    {{ now()->format('Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Counters Section -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Status Loket</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($counters as $counter)
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-gray-800">{{ $counter->name }}</h4>
                    <p class="text-sm text-gray-600 mb-2">{{ $counter->description }}</p>
                    <div class="flex flex-wrap gap-1 mb-2">
                        @foreach ($counter->services as $service)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                {{ $service->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="text-sm">
                        <span class="text-green-600">
                            Aktif: {{ $counter->antrians->count() }} antrian
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}
    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 mb-8">
        <!-- Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Antrian Harian</h3>
            <canvas id="queueChart" width="400" height="200"></canvas>
        </div>
    </div>



    {{-- <!-- Recent Queue -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-700">Antrian Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loket
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($recentAntrian as $antrian)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $antrian->nomor_antrian }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $antrian->service->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $antrian->status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $antrian->status == 'diproses' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $antrian->status == 'pending' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($antrian->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $antrian->counter ? $antrian->counter->name : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $antrian->created_at->format('H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> --}}

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                const ctx = document.getElementById('queueChart').getContext('2d');
                const chartData = @json($chartData);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Jumlah Antrian',
                            data: chartData.data,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</div>
