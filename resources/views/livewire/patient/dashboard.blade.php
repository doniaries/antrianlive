<div class="container mx-auto px-4 py-6">
    <!-- Info Antrian Aktif -->
    @if ($antrianAktif ?? false)
        <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="h-5 w-5 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-100">
                        Antrian Aktif Anda
                    </h3>
                    <div class="mt-1 text-sm text-blue-700 dark:text-blue-200">
                        <p class="font-semibold">
                            No. Antrian: <span class="text-lg">{{ $antrianAktif->nomor_antrian }}</span>
                        </p>
                        <p class="mt-1">
                            Layanan: {{ $antrianAktif->layanan->nama_layanan ?? 'Layanan' }}
                        </p>
                        <p class="mt-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $antrianAktif->status === 'dipanggil'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                {{ ucfirst($antrianAktif->status) }}
                                @if ($antrianAktif->status === 'dipanggil')
                                    <svg class="ml-1 w-3 h-3 animate-pulse inline" fill="currentColor"
                                        viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Antrian Hari Ini</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $antrianHariIni ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="ml-4">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Menunggu Dipanggil</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $antrianMenunggu ?? 0 }}</p>
        </div>
    </div>
</div>
</div>

<div class="mt-8">
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Riwayat Antrian Terakhir</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">5 antrian terakhir yang Anda buat
                    </p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Waktu</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No. Antrian</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Layanan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse(($riwayatAntrian ?? []) as $antrian)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $antrian->created_at->format('d M Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ $antrian->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $antrian->nomor_antrian }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ $antrian->layanan->nama_layanan ?? 'Layanan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses =
                                            [
                                                'menunggu' =>
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                'dipanggil' =>
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'selesai' =>
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                            ][$antrian->status] ??
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                    @endphp
                                    <span
                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ ucfirst($antrian->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada
                                        riwayat</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Anda belum memiliki riwayat antrian.
                                    </p>
                                    <div class="mt-6">
                                        <a href="{{ route('ambil-tiket') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v-3a1 1 0 01-1 0h-3M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Ambil Tiket Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
