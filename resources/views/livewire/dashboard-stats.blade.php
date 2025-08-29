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

        <!-- Greeting Card -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                @php
                    $hour = now()->hour;
                    $greeting = 'Selamat ';

                    if ($hour < 10) {
                        $greeting .= 'Pagi';
                    } elseif ($hour < 15) {
                        $greeting .= 'Siang';
                    } elseif ($hour < 19) {
                        $greeting .= 'Sore';
                    } else {
                        $greeting .= 'Malam';
                    }

                    $greeting .= ', ' . (auth()->user()->name ?? 'Pengguna');
                @endphp

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $greeting }}!</h2>
                <p class="text-gray-600 dark:text-gray-300">Selamat datang di Sistem Antrian Online</p>

                @if (isset($nextTicket))
                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Antrian Anda Selanjutnya:</p>
                        <p class="text-lg font-semibold text-blue-700 dark:text-blue-300">#{{ $nextTicket->ticket_number }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Layanan: {{ $nextTicket->service->name ?? 'Umum' }}</p>
                    </div>
                @endif
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Tiket Hari Ini -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tiket</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $statistics['total_today'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dalam Antrian -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $statistics['waiting'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Selesai -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $statistics['finished'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Waktu -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata Waktu</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $statistics['avg_wait_time'] }} menit</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
