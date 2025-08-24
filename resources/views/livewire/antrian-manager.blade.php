<div>
    <!-- Audio Elements -->
    <audio id="callSound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>
    
    <!-- Current Call Display -->
    <div id="currentCall" class="fixed bottom-4 right-4 bg-white dark:bg-zinc-800 rounded-xl shadow-xl p-4 w-80 z-50 hidden">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Antrian Saat Ini</h3>
            <button onclick="closeCurrentCall()" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="currentCallContent" class="text-center">
            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2" id="currentNumber">A-001</div>
            <div class="text-sm text-zinc-600 dark:text-zinc-300" id="currentService">Layanan</div>
            <div class="text-sm text-zinc-500 dark:text-zinc-400" id="currentCounter">Loket 1</div>
            <div class="mt-4 flex justify-center space-x-2">
                <button onclick="playCallSound()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 001.414 1.414"></path>
                    </svg>
                    Panggil Ulang
                </button>
                <button onclick="closeCurrentCall()" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-zinc-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition-colors">
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
            {{-- <button wire:click="create"
                class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl shadow-lg hover:from-blue-600 hover:to-blue-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Layanan
            </button> --}}
        </div>
    </div>


    <main class="p-4 sm:p-6 space-y-6">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('message') }}
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
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl p-4 shadow-lg">
                <div class="text-center">
                    <button wire:click="openModal"
                        class="w-full text-white font-medium hover:scale-105 transition-transform duration-200">
                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Antrian
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tanggal</label>
                    <input type="date" wire:model.live="filterDate"
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Layanan</label>
                    <select wire:model.live="filterService"
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Layanan</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Status</label>
                    <select wire:model.live="filterStatus"
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="waiting">Menunggu</option>
                        <option value="called">Dipanggil</option>
                        <option value="finished">Selesai</option>
                        <option value="skipped">Dilewati</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button wire:click="$refresh"
                        class="inline-flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-md font-semibold text-xs text-zinc-700 dark:text-zinc-300 uppercase tracking-widest hover:bg-zinc-50 dark:hover:bg-zinc-600 focus:outline-none focus:border-zinc-700 focus:ring focus:ring-zinc-200 disabled:opacity-25 transition w-full justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Refresh
                    </button>
                </div>
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
                                        <div class="font-medium text-sm text-zinc-900 dark:text-zinc-100">
                                            {{ $antrian->service->name }}</div>
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
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
                                    <div class="flex space-x-1">
                                        @if ($antrian->status === 'waiting')
                                            <button
                                                wire:click="callNext({{ $antrian->id }}, {{ $antrian->service_id }}, {{ $antrian->counter_id ?? 1 }})"
                                                onclick="callNumber('{{ $antrian->formatted_number }}', '{{ $antrian->service->name }}', '{{ $antrian->counter->name ?? 'Umum' }}')"
                                                class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 p-1"
                                                title="Panggil">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                            </button>
                                        @elseif($antrian->status === 'called')
                                            <button 
                                                onclick="showCurrentCall('{{ $antrian->formatted_number }}', '{{ $antrian->service->name }}', '{{ $antrian->counter->name ?? 'Umum' }}')"
                                                class="text-yellow-500 hover:text-yellow-700 dark:hover:text-yellow-400 p-1 mr-1"
                                                title="Panggil Ulang">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </button>
                                            <button wire:click="skip({{ $antrian->id }})"
                                                class="text-orange-500 hover:text-orange-700 dark:hover:text-orange-400 p-1 mr-1"
                                                title="Lewati">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            <button wire:click="finish({{ $antrian->id }})"
                                                class="text-green-500 hover:text-green-700 dark:hover:text-green-400 p-1"
                                                title="Selesai">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        <button wire:click="edit({{ $antrian->id }})"
                                            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 p-1"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $antrian->id }})"
                                            class="text-red-400 hover:text-red-600 dark:hover:text-red-300 p-1"
                                            title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus antrian ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
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

        <!-- Modal -->
        @if ($showModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" wire:loading.attr="class"
                wire:loading.class="opacity-100" wire:loading.remove.class="opacity-0" x-cloak>

                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div
                        class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl">

                        <!-- Close button -->
                        <button wire:click="closeModal"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'create' }}" class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                                {{ $isEditMode ? 'Edit Antrian' : 'Tambah Antrian' }}
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

                                @if ($isEditMode)
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            Status
                                        </label>
                                        <select wire:model="status" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150">
                                            <option value="waiting">Menunggu</option>
                                            <option value="called">Dipanggil</option>
                                            <option value="finished">Selesai</option>
                                            <option value="skipped">Dilewati</option>
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end space-x-3 mt-8">
                                <button type="button" wire:click="closeModal"
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-colors duration-150">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                    {{ $isEditMode ? 'Perbarui' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

@push('scripts')
<script>
    // Inisialisasi audio element
    const callSound = document.getElementById('callSound');
    
    // Fungsi untuk memainkan suara panggilan
    function playCallSound() {
        callSound.currentTime = 0; // Reset audio ke awal
        callSound.play().catch(e => console.error('Error playing sound:', e));
    }
    
    // Fungsi untuk menampilkan notifikasi panggilan
    function showCurrentCall(number, service, counter) {
        document.getElementById('currentNumber').textContent = number;
        document.getElementById('currentService').textContent = service;
        document.getElementById('currentCounter').textContent = counter;
        document.getElementById('currentCall').classList.remove('hidden');
        playCallSound();
        
        // Baca nomor dengan Web Speech API
        speakNumber(number, service, counter);
    }
    
    // Fungsi untuk menutup notifikasi panggilan
    function closeCurrentCall() {
        document.getElementById('currentCall').classList.add('hidden');
    }
    
    // Fungsi untuk memanggil nomor antrian
    function callNumber(number, service, counter) {
        showCurrentCall(number, service, counter);
    }
    
    // Fungsi untuk membaca nomor antrian dengan Web Speech API
    function speakNumber(number, service, counter) {
        if ('speechSynthesis' in window) {
            const speech = new SpeechSynthesisUtterance();
            speech.text = `Nomor antrian ${number.split('-').join(' ')} silakan menuju ${counter}`;
            speech.lang = 'id-ID';
            speech.rate = 0.9;
            
            // Coba gunakan suara yang tersedia
            const voices = window.speechSynthesis.getVoices();
            const idVoice = voices.find(voice => voice.lang === 'id-ID') || voices[0];
            if (idVoice) speech.voice = idVoice;
            
            window.speechSynthesis.speak(speech);
        }
    }
    
    // Event listener untuk Livewire
    document.addEventListener('livewire:initialized', () => {
        @this.on('antrian-called', (event) => {
            showCurrentCall(event.number, event.service, event.counter);
        });
    });
    
    // Inisialisasi suara saat halaman dimuat
    window.speechSynthesis.onvoiceschanged = function() {
        // Suara sudah dimuat
    };
</script>
@endpush
