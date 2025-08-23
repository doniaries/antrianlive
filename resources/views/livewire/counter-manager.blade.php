<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6 transition-colors duration-200">
    <!-- Header -->
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manajemen Loket</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola daftar loket pelayanan dan layanan yang
                ditangani</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div
                class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 rounded-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Daftar Loket</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total {{ $counters->total() }} loket</p>
            </div>
            <button wire:click="openModal"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah Loket
            </button>
        </div>

        <!-- Counters Table -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg transition-colors duration-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Loket
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Deskripsi
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Layanan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Dibuat
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($counters as $counter)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $counter->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $counter->description ? Str::limit($counter->description, 50) : '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($counter->services as $service)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                                {{ $service->code }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $counter->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="edit({{ $counter->id }})"
                                            class="text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 p-1 rounded-full hover:bg-yellow-50 dark:hover:bg-yellow-900/30"
                                            title="Edit">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $counter->id }})"
                                            class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30"
                                            title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus loket ini? Semua layanan yang terkait akan dilepas.')">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada data loket
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($counters->hasPages())
                <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    {{ $counters->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ show: false }" x-init="// Initialize from Livewire
    $watch('$wire.showModal', value => {
        show = value;
        if (value) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    });

    // Initial sync
    $nextTick(() => {
        show = $wire.showModal;
    });" x-show="show" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-cloak class="fixed z-50 inset-0 overflow-y-auto"
        aria-labelledby="modal-title" aria-modal="true" style="display: none"
        :style="{ display: show ? 'block' : 'none' }">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.closeModal()"
                aria-hidden="true">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            {{ $isEditMode ? 'Edit Loket' : 'Tambah Loket' }}
                        </h3>

                        <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="mt-5 space-y-6">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                    Loket</label>
                                <input type="text" wire:model="name" id="name" required
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi
                                    (Opsional)</label>
                                <textarea wire:model="description" id="description" rows="3"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Layanan
                                    yang Ditangani</label>
                                <div class="space-y-2">
                                    @foreach ($services as $service)
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model="selectedServices"
                                                value="{{ $service->id }}" id="service-{{ $service->id }}"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="service-{{ $service->id }}"
                                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                {{ $service->name }} ({{ $service->code }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Pilih layanan yang akan ditangani oleh loket ini
                                </p>
                                @error('selectedServices')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="button" wire:click="closeModal"
                                    class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                                    @click="show = false">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-2 sm:text-sm">
                                    {{ $isEditMode ? 'Perbarui' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
