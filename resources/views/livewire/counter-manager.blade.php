<div x-data="{
    showConfirmModal: false,
    confirmTitle: '',
    confirmMessage: '',
    confirmAction: null,
    openConfirm(title, message, action) {
        this.confirmTitle = title;
        this.confirmMessage = message;
        this.confirmAction = action;
        this.showConfirmModal = true;
    },
    executeConfirm() {
        if (this.confirmAction) {
            this.confirmAction();
        }
        this.showConfirmModal = false;
    }
}">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manajemen Loket</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola loket pelayanan dan layanan yang
                    ditangani</p>
            </div>
            <button wire:click="openModal"
                class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Loket
            </button>
        </div>
    </div>

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
                <button @click="show = false" class="text-white hover:text-gray-100 ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Counters Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-6">
        {{-- <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Loket</h2>
        </div> --}}

        <div
            class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Nama Loket
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Layanan
                        </th>
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Dibuat
                        </th> --}}
                        <th
                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($counters as $counter)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $counter->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs break-words">
                                {{ $counter->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($counter->services as $service)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900 dark:to-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700">
                                            {{ $service->code }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusColors = [
                                        'buka' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'tutup' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'istirahat' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                    ];
                                    $statusLabels = [
                                        'buka' => 'Buka',
                                        'tutup' => 'Tutup',
                                        'istirahat' => 'Istirahat'
                                    ];
                                    $statusClass = $statusColors[$counter->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                    $statusLabel = $statusLabels[$counter->status] ?? 'Tidak Diketahui';
                                @endphp
                                <div class="flex flex-col items-center space-y-1">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }} whitespace-nowrap">
                                        {{ $statusLabel }}
                                    </span>
                                    @if($counter->open_time && $counter->close_time)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $counter->open_time }} - {{ $counter->close_time }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- Toggle Switch -->
                                    <div 
                                        x-data="{ isActive: {{ $counter->status === 'buka' ? 'true' : 'false' }} }"
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-200 cursor-pointer"
                                        :class="{ 'bg-green-500': isActive, 'bg-gray-300 dark:bg-gray-600': !isActive }"
                                        @click="
                                            isActive = !isActive;
                                            $wire.call('update', {{ $counter->id }});
                                        "
                                        x-tooltip="Klik untuk {{ $counter->status === 'buka' ? 'menonaktifkan' : 'mengaktifkan' }} loket"
                                    >
                                        <input 
                                            type="checkbox" 
                                            class="absolute w-0 h-0 opacity-0"
                                            :checked="isActive"
                                        >
                                        <span 
                                            class="inline-block w-4 h-4 transform transition-transform duration-200 ease-in-out bg-white rounded-full shadow-md"
                                            :class="{ 'translate-x-6': isActive, 'translate-x-1': !isActive }"
                                        >
                                        </span>
                                        <span class="sr-only">
                                            {{ $counter->status === 'buka' ? 'Nonaktifkan' : 'Aktifkan' }} Loket
                                        </span>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                    <button wire:click="edit({{ $counter->id }})"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105"
                                        title="Edit">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button
                                        @click="openConfirm('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus loket {{ $counter->name }}? Semua layanan yang terkait akan dilepas.', () => $wire.delete({{ $counter->id }}))"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105"
                                        title="Hapus">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $counters->links() }}
        </div>

        <!-- Modal -->
        <div x-data="{ showModal: @entangle('showModal') }" x-show="showModal" x-cloak
            x-on:keydown.escape.window="showModal = false; $wire.closeModal()"
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 transition-opacity" x-show="showModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click="showModal = false; $wire.closeModal()">
            </div>

            <!-- Modal panel -->
            <div class="flex min-h-screen items-center justify-center p-4" x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div
                    class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl">

                    <!-- Close button -->
                    <button @click="showModal = false; $wire.closeModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-150"
                        type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" x-data="{ submitting: false }" class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            {{ $isEditMode ? 'Edit Loket' : 'Tambah Loket' }}
                        </h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Loket
                                </label>
                                <input wire:model="name" type="text" placeholder="Contoh: Loket 1"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Deskripsi
                                </label>
                                <textarea wire:model="description" placeholder="Deskripsi loket (opsional)" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Layanan yang Ditangani
                                </label>
                                <div class="space-y-3">
                                    @foreach ($services as $service)
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="selectedServices"
                                                value="{{ $service->id }}"
                                                class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 transition-colors duration-150">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $service->name }} ({{ $service->code }})
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Pilih layanan yang akan ditangani oleh loket ini
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Status Loket
                                    </label>
                                    <select wire:model="status"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150">
                                        <option value="buka">Buka</option>
                                        <option value="tutup">Tutup</option>
                                        <option value="istirahat">Istirahat</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Jam Buka
                                    </label>
                                    <input wire:model="open_time" type="time"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150">
                                </div>
                                
                                <div class="col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Jam Tutup
                                    </label>
                                    <input wire:model="close_time" type="time"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-150">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Status "Buka" akan otomatis menyesuaikan dengan jam operasional yang ditentukan
                            </p>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="showModal = false; $wire.closeModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500 transition-colors duration-150">
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

        <!-- Confirmation Modal -->
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" x-show="showConfirmModal"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            </div>

            <!-- Modal panel -->
            <div class="flex min-h-screen items-center justify-center p-4" x-show="showConfirmModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div
                    class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl ring-1 ring-black/5">

                    <!-- Icon -->
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-r from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/50 mt-8">
                        <svg class="h-10 w-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="mt-4 text-center px-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="confirmTitle"></h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" x-text="confirmMessage"></p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex gap-3 px-8 pb-8">
                        <button @click="showConfirmModal = false"
                            class="flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                            Batal
                        </button>
                        <button @click="executeConfirm()"
                            class="flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-200">
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
