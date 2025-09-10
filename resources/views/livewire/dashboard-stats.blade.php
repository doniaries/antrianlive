<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Antrian -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Total Antrian</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAntrianHariIni }}</p>
                </div>
            </div>
        </div>

        <!-- Antrian Selesai -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Selesai</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $antrianSelesai }}</p>
                </div>
            </div>
        </div>

        <!-- Antrian Diproses -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Diproses</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $antrianDiproses }}</p>
                </div>
            </div>
        </div>

        <!-- Antrian Menunggu -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Menunggu</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $antrianDitunda }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Counters Section -->
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
    </div>
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
