<div>
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 px-4 py-4 sm:px-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">Manajemen Antrian</h1>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Sistem pengelolaan antrian pelayanan</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Nomor Antrian
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Layanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Loket
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($antrians as $antrian)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-lg text-zinc-900 dark:text-zinc-100">
                                    {{ $antrian->formatted_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium text-sm text-zinc-900 dark:text-zinc-100">{{ $antrian->service->name }}</div>
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400">{{ $antrian->service->code }}</div>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $antrian->status === 'waiting' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : ($antrian->status === 'called' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : ($antrian->status === 'finished' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300')) }}">
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
                                            <button wire:click="callNext({{ $antrian->service_id }}, {{ $antrian->counter_id ?? 1 }})" 
                                                    class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 p-1" title="Panggil">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                                </svg>
                                            </button>
                                        @elseif($antrian->status === 'called')
                                            <button wire:click="finish({{ $antrian->id }})" 
                                                    class="text-green-400 hover:text-green-600 dark:hover:text-green-300 p-1" title="Selesai">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                            <button wire:click="skip({{ $antrian->id }})" 
                                                    class="text-orange-400 hover:text-orange-600 dark:hover:text-orange-300 p-1" title="Lewati">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        <button wire:click="edit({{ $antrian->id }})" 
                                                class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 p-1" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $antrian->id }})" 
                                                class="text-red-400 hover:text-red-600 dark:hover:text-red-300 p-1" title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus antrian ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
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
        @if($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeModal">
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl w-full max-w-lg mx-4" wire:click.stop>
                    <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                            {{ $isEditMode ? 'Edit Antrian' : 'Tambah Antrian' }}
                        </h3>
                    </div>
                    
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'create' }}" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Layanan</label>
                            <select wire:model="selectedService" required 
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Layanan</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Loket (Opsional)</label>
                            <select wire:model="selectedCounter" 
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Loket</option>
                                @foreach ($counters as $counter)
                                    <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($isEditMode)
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Status</label>
                                <select wire:model="status" required 
                                        class="w-full rounded-md border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="waiting">Menunggu</option>
                                    <option value="called">Dipanggil</option>
                                    <option value="finished">Selesai</option>
                                    <option value="skipped">Dilewati</option>
                                </select>
                            </div>
                        @endif

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" wire:click="closeModal" 
                                    class="inline-flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-md font-semibold text-xs text-zinc-700 dark:text-zinc-300 uppercase tracking-widest hover:bg-zinc-50 dark:hover:bg-zinc-600 focus:outline-none focus:border-zinc-700 focus:ring focus:ring-zinc-200 disabled:opacity-25 transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:border-blue-600 dark:focus:ring-blue-400">
                                {{ $isEditMode ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </main>
</div>
