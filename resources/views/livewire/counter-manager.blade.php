<div>
    <flux:header>
        <flux:heading size="xl">Manajemen Loket</flux:heading>
        {{-- <flux:subheading>Kelola loket pelayanan dan layanan yang ditangani</flux:subheading> --}}
    </flux:header>

    <flux:main class="space-y-6">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <flux:heading size="lg">Daftar Loket</flux:heading>
            <flux:button wire:click="openModal" variant="primary">
                <flux:icon.plus class="mr-2" />
                Tambah Loket
            </flux:button>
        </div>

        <!-- Counters Table -->
        <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama
                            Loket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($counters as $counter)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $counter->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs break-words">
                                {{ $counter->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($counter->services as $service)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                            {{ $service->code }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $counter->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="edit({{ $counter->id }})"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1" title="Edit">
                                        <flux:icon.pencil class="w-4 h-4" />
                                    </button>
                                    <button wire:click="delete({{ $counter->id }})"
                                        class="text-red-400 hover:text-red-600 dark:hover:text-red-300 p-1" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus loket ini? Semua layanan yang terkait akan dilepas.')">
                                        <flux:icon.trash class="w-4 h-4" />
                                    </button>
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
        <flux:modal wire:model="showModal" class="w-full max-w-lg">
            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $isEditMode ? 'Edit Loket' : 'Tambah Loket' }}
                    </flux:heading>
                </div>

                <flux:input wire:model="name" label="Nama Loket" placeholder="Contoh: Loket 1" required />

                <flux:textarea wire:model="description" label="Deskripsi" placeholder="Deskripsi loket (opsional)"
                    rows="3" />

                <div>
                    <flux:label>Layanan yang Ditangani</flux:label>
                    <div class="mt-2 space-y-2">
                        @foreach ($services as $service)
                            <flux:checkbox wire:model="selectedServices" value="{{ $service->id }}"
                                label="{{ $service->name }} ({{ $service->code }})" />
                        @endforeach
                    </div>
                    <flux:subheading class="mt-1">
                        Pilih layanan yang akan ditangani oleh loket ini
                    </flux:subheading>
                </div>

                <div class="flex justify-end space-x-4">
                    <flux:button type="button" variant="ghost" wire:click="closeModal">
                        Batal
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ $isEditMode ? 'Perbarui' : 'Simpan' }}
                    </flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:main>
</div>
