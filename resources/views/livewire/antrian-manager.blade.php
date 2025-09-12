<div>
    <!-- Audio Elements -->
    <audio id="callSound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>

    <!-- Current Call Display -->
    <div id="currentCall"
        class="fixed bottom-4 right-4 bg-white dark:bg-zinc-800 rounded-xl shadow-xl p-4 w-80 z-50 hidden">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Antrian Saat Ini</h3>
            <button onclick="closeCurrentCall()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div id="currentCallContent" class="text-center">
            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2" id="currentNumber">-</div>
            <div class="text-sm text-zinc-600 dark:text-zinc-300" id="currentService">Layanan</div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400" id="currentCounter">Loket</div>
            <div class="mt-4 flex justify-center space-x-2">
                <button onclick="playCallSound()"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414">
                        </path>
                    </svg>
                    Panggil Ulang
                </button>
                <button onclick="closeCurrentCall()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-zinc-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manajemen Antrian</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola antrian yang ada di sistem
                    antrian</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @if (auth()->user()->isSuperAdmin())
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                    Super Admin
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    Petugas
                                </span>
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto refresh indicator -->
    <div wire:poll.3000ms class="hidden"></div>

    <main class="p-4 sm:p-6 space-y-6">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full">
                <div class="bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                    <button @click="show = false" class="text-white hover:text-gray-100 ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 shadow-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $this->waitingCount }}</div>
                    <div class="text-sm text-blue-100">Menunggu</div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 shadow-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $this->calledCount }}</div>
                    <div class="text-sm text-yellow-100">Dipanggil</div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 shadow-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $this->finishedCount }}</div>
                    <div class="text-sm text-green-100">Selesai</div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 shadow-lg">
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ $this->skippedCount }}</div>
                    <div class="text-sm text-orange-100">Dilewati</div>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div
            class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm p-4 mb-4">
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Filter:</span>
                <button wire:click="$set('filterStatus', '')"
                    class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-150 {{ empty($filterStatus) ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-600' }}">
                    Semua
                </button>
                <button wire:click="$set('filterStatus', 'waiting')"
                    class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-150 {{ $filterStatus === 'waiting' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-600' }}">
                    Menunggu
                </button>
                <button wire:click="$set('filterStatus', 'called')"
                    class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-150 {{ $filterStatus === 'called' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-600' }}">
                    Dipanggil
                </button>
                <button wire:click="$set('filterStatus', 'finished')"
                    class="px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-150 {{ $filterStatus === 'finished' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-600' }}">
                    Selesai & Lewati
                </button>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <label class="text-sm text-zinc-600 dark:text-zinc-400">Tanggal:</label>
                <input type="date" wire:model.live="filterDate"
                    class="px-2 py-1 text-xs border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>

        <!-- Antrians Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Nomor Antrian
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Layanan
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Loket
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($antrians as $antrian)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <td
                                    class="px-6 py-4 whitespace-nowrap font-bold text-lg text-zinc-900 dark:text-zinc-100">
                                    {{ $antrian->formatted_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium text-sm">{{ $antrian->service->name }}</div>
                                        <div
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @php
$serviceCode = $antrian->service->code;
                                                $colorMap = [
                                                    'PU' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'PS' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'PA' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                ];

                                                if (isset($colorMap[$serviceCode])) {
                                                    $colorClass = $colorMap[$serviceCode];
                                                } else {
                                                    $hash = crc32($serviceCode);
                                                    $colors = [
                                                        'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                        'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
                                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                                        'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                                        'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-300',
                                                        'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
                                                        'bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-300',
                                                    ];
                                                    $colorClass = $colors[abs($hash) % count($colors)];
                                                } @endphp
                                            {{ $colorClass }}
                                        ">
                                            {{ $antrian->service->code }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                    @if ($antrian->counter)
                                        {{ $antrian->counter->name }}
                                    @else
                                        <span class="text-zinc-500 dark:text-zinc-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $antrian->status === 'waiting' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : ($antrian->status === 'called' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : ($antrian->status === 'finished' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300')) }}">
                                        {{ $antrian->status === 'waiting' ? 'Menunggu' : ($antrian->status === 'called' ? 'Dipanggil' : ($antrian->status === 'finished' ? 'Selesai' : 'Dilewati')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    <div class="text-sm">
                                        <div>{{ $antrian->created_at->format('d/m/Y') }}</div>
                                        <div>{{ $antrian->created_at->format('H:i:s') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col gap-2">
                                        @if ($antrian->status === 'waiting')
                                            @php
                                                $hasActiveQueue =
                                                    $antrians
                                                        ->where('service_id', $antrian->service_id)
                                                        ->where('counter_id', $antrian->counter_id)
                                                        ->where('status', 'called')
                                                        ->where('id', '!=', $antrian->id)
                                                        ->count() > 0;
                                            @endphp
                                            <button
                                                wire:click="callNext({{ $antrian->id }}, {{ $antrian->service_id }}, {{ $antrian->counter_id ?? 1 }})"
                                                class="w-full px-3 py-1.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-md hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-100 dark:disabled:hover:bg-blue-900"
                                                title="Panggil" @if ($hasActiveQueue) disabled @endif>
                                                Panggil
                                            </button>
                                        @elseif($antrian->status === 'called')
                                            <button wire:click="recall({{ $antrian->id }})"
                                                class="w-full px-3 py-1.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-200 dark:hover:bg-yellow-800 transition-colors"
                                                title="Panggil Ulang">
                                                Panggil Ulang
                                            </button>

                                            <div class="flex gap-2">
                                                <button wire:click="skip({{ $antrian->id }})"
                                                    class="flex-1 px-3 py-1.5 text-xs font-medium bg-orange-500 text-white rounded-md hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 transition-colors"
                                                    title="Lewati">
                                                    Lewati
                                                </button>
                                                <button wire:click="finish({{ $antrian->id }})"
                                                    class="flex-1 px-3 py-1.5 text-xs font-medium bg-green-500 text-white rounded-md hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition-colors"
                                                    title="Selesai">
                                                    Selesai
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-500 mx-auto mb-2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                        </path>
                                    </svg>
                                    <div class="text-zinc-500 dark:text-zinc-400">Tidak ada antrian</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $antrians->links() }}
            </div>
        </div>

    </main>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-hidden" x-cloak>
            <div class="absolute inset-0 bg-black/50 transition-opacity duration-300" wire:click="closeModal">
            </div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="relative w-full max-w-md">
                    <div class="transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl">

                        <!-- Close button -->
                        <button wire:click="closeModal"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <form wire:submit.prevent="create" class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                                Tambah Antrian
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Layanan
                                    </label>
                                    <select wire:model="selectedService" required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150">
                                        <option value="">Pilih Layanan</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}
                                                ({{ $service->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Loket (Opsional)
                                    </label>
                                    <select wire:model="selectedCounter"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150">
                                        <option value="">Pilih Loket</option>
                                        @foreach ($counters as $counter)
                                            <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="flex justify-end space-x-3 mt-8">
                                <button type="button" wire:click="closeModal"
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-colors duration-150">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        // Inisialisasi audio element
        const callSound = document.getElementById('callSound');
        let speechSynthesis = window.speechSynthesis;
        let audioInitialized = false;

        // Fungsi untuk inisialisasi audio (untuk menghindari blokir autoplay)
        function initializeAudio() {
            if (audioInitialized) return Promise.resolve();

            return new Promise((resolve, reject) => {
                try {
                    // Set volume ke 0 untuk inisialisasi
                    callSound.volume = 0;
                    callSound.play().then(() => {
                        callSound.pause();
                        callSound.currentTime = 0;
                        callSound.volume = 1; // Kembalikan volume ke normal
                        audioInitialized = true;
                        console.log('Audio initialized successfully');
                        resolve();
                    }).catch(error => {
                        console.log('Audio initialization failed, will try on user interaction:', error);
                        resolve(); // Tetap resolve agar tidak block
                    });
                } catch (error) {
                    console.log('Audio initialization error:', error);
                    resolve();
                }
            });
        }

        // Inisialisasi audio saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            initializeAudio();
        });

        // Fungsi untuk menampilkan notifikasi toast
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-amber-500' : 'bg-blue-500'
            }`;

            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' : ''}
                        ${type === 'error' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' : ''}
                        ${type === 'warning' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>' : ''}
                        ${type === 'info' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' : ''}
                    </svg>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // Fungsi untuk memainkan suara panggilan dengan penanganan error yang lebih baik
        async function playCallSound() {
            try {
                // Jika audio belum diinisialisasi, coba inisialisasi terlebih dahulu
                if (!audioInitialized) {
                    await initializeAudio();
                }

                // Reset audio ke awal
                callSound.currentTime = 0;

                // Coba mainkan audio
                const playPromise = callSound.play();

                if (playPromise !== undefined) {
                    return playPromise.catch(error => {
                        console.warn('Audio play failed:', error);
                        // Jika gagal karena autoplay policy, coba lagi dengan user gesture
                        return new Promise((resolve) => {
                            // Tambahkan event listener untuk user interaction
                            const playOnInteraction = () => {
                                callSound.play().then(() => {
                                    console.log('Audio played after user interaction');
                                    resolve();
                                }).catch(err => {
                                    console.error('Audio still failed after interaction:',
                                        err);
                                    resolve(); // Tetap resolve agar tidak block
                                });
                                document.removeEventListener('click', playOnInteraction);
                                document.removeEventListener('touchstart', playOnInteraction);
                            };

                            document.addEventListener('click', playOnInteraction, {
                                once: true
                            });
                            document.addEventListener('touchstart', playOnInteraction, {
                                once: true
                            });

                            // Tampilkan pesan ke user
                            console.log('Click anywhere to enable audio');
                            resolve();
                        });
                    });
                }

                return playPromise;
            } catch (e) {
                console.error('Error playing sound:', e);
                return Promise.resolve();
            }
        }

        // Fungsi untuk menampilkan notifikasi panggilan
        function showCurrentCall(number, service, counter) {
            try {
                const currentCall = document.getElementById('currentCall');
                if (!currentCall) return;

                const numberEl = document.getElementById('currentNumber');
                const serviceEl = document.getElementById('currentService');
                const counterEl = document.getElementById('currentCounter');

                if (numberEl) numberEl.textContent = number;
                if (serviceEl) serviceEl.textContent = service;
                if (counterEl) counterEl.textContent = counter;

                currentCall.classList.remove('hidden');

                playCallSound().then(() => {
                    setTimeout(() => {
                        speakNumber(number, service, counter);
                    }, 100);
                }).catch(error => {
                    console.error('Error playing call sound:', error);
                    setTimeout(() => {
                        speakNumber(number, service, counter);
                    }, 100);
                });
            } catch (error) {
                console.error('Error in showCurrentCall:', error);
            }
        }

        // Fungsi untuk menutup notifikasi panggilan
        function closeCurrentCall() {
            document.getElementById('currentCall').classList.add('hidden');
            if (speechSynthesis) {
                speechSynthesis.cancel();
            }
        }

        // Fungsi untuk membaca nomor antrian dengan Web Speech API
        function speakNumber(number, service, counter) {
            try {
                if (!speechSynthesis) return;

                speechSynthesis.cancel();

                const numberParts = number.split('-');
                const prefix = numberParts[0];
                const queueNumber = numberParts[1] || '';

                const numberToWords = (num) => {
                    const ones = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
                    const tens = ['', 'sepuluh', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh',
                        'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'
                    ];
                    const teens = ['sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
                        'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
                    ];

                    if (num === 0) return 'nol';
                    if (num < 10) return ones[num];
                    if (num < 20) return teens[num - 10];

                    const ten = Math.floor(num / 10);
                    const one = num % 10;
                    return tens[ten] + (one ? ' ' + ones[one] : '');
                };

                let numberText = '';
                if (prefix) {
                    numberText += prefix.split('').join(' ') + ' ';
                }

                const digits = queueNumber.split('');
                const digitWords = digits.map(d => {
                    const num = parseInt(d);
                    return isNaN(num) ? d : numberToWords(num);
                });

                numberText += digitWords.join(' ');

                const textToSpeak = `Nomor antrian ${numberText}, silakan menuju ${counter}`;

                const utterance = new SpeechSynthesisUtterance(textToSpeak);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1.0;

                const speakWithVoice = () => {
                    try {
                        const voices = speechSynthesis.getVoices();
                        if (voices.length > 0) {
                            const femaleVoice = voices.find(voice =>
                                voice.lang === 'id-ID' && voice.name.includes('female')
                            );
                            utterance.voice = femaleVoice || voices[0];
                        }

                        utterance.onerror = (event) => {
                            console.error('Error in speech synthesis:', event);
                        };

                        speechSynthesis.speak(utterance);
                    } catch (error) {
                        console.error('Error in speakWithVoice:', error);
                    }
                };

                if (speechSynthesis.onvoiceschanged !== undefined) {
                    speechSynthesis.onvoiceschanged = speakWithVoice;
                }

                speakWithVoice();

            } catch (error) {
                console.error('Error in speakNumber:', error);
            }
        }

        // Fungsi baru untuk memainkan suara dari dashboard
        function playQueueSound(number, service, counter) {
            try {
                playCallSound().then(() => {
                    setTimeout(() => {
                        speakNumber(number, service, counter);
                    }, 100);
                }).catch(error => {
                    console.error('Error playing queue sound:', error);
                    setTimeout(() => {
                        speakNumber(number, service, counter);
                    }, 100);
                });
            } catch (error) {
                console.error('Error in playQueueSound:', error);
            }
        }

        // Fungsi global untuk memanggil antrian dengan suara
        window.handleQueueCall = function(number, service, counter) {
            playQueueSound(number, service, counter);
        };

        // Event listener untuk Livewire
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('antrian-called', (event) => {
                setTimeout(() => {
                    showCurrentCall(event.detail.number, event.detail.service, event.detail
                        .counter);
                }, 100);
            });

            Livewire.on('notify', (event) => {
                setTimeout(() => {
                    showToast(event.detail.type, event.detail.message);
                }, 100);
            });

            Livewire.on('call-queue', (event) => {
                setTimeout(() => {
                    showCurrentCall(event.detail.number, event.detail.service, event.detail
                        .counter);
                }, 100);
            });

            Livewire.on('error', (event) => {
                setTimeout(() => {
                    showToast('error', event.detail.message);
                }, 100);
            });
        });

        // Inisialisasi voices
        if (speechSynthesis) {
            if (speechSynthesis.onvoiceschanged !== undefined) {
                speechSynthesis.onvoiceschanged = function() {
                    console.log('Voices loaded');
                };
            }
        }
    </script>
@endpush
