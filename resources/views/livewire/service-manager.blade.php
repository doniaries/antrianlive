<div>
    <flux:header>
        <flux:heading size="xl">Manajemen Layanan</flux:heading>

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
            <flux:heading size="lg">Daftar Layanan</flux:heading>
            <flux:button wire:click="openModal" variant="primary"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white dark:text-white">
                <flux:icon.plus class="mr-2" />
                Tambah Layanan
            </flux:button>
        </div>

        <!-- Services Table -->
        <div class="overflow-hidden bg-white shadow-sm rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $service->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    {{ $service->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $service->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="toggleStatus({{ $service->id }})"
                                        class="text-gray-400 hover:text-gray-600 p-1"
                                        title="{{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if ($service->is_active)
                                            <flux:icon.bolt-slash class="w-4 h-4 text-green-600" />
                                        @else
                                            <flux:icon.bolt class="w-4 h-4 text-gray-400" />
                                        @endif
                                    </button>
                                    <button wire:click="edit({{ $service->id }})"
                                        class="text-gray-400 hover:text-gray-600 p-1" title="Edit">
                                        <flux:icon.pencil class="w-4 h-4" />
                                    </button>
                                    <button wire:click="delete({{ $service->id }})"
                                        class="text-red-400 hover:text-red-600 p-1" title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
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
            {{ $services->links() }}
        </div>

        <!-- Modal -->
        <flux:modal wire:model="showModal" class="w-full max-w-md">
            <form wire:submit="store" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $isEditMode ? 'Edit Layanan' : 'Tambah Layanan' }}
                    </flux:heading>
                </div>

                <flux:input wire:model="name" label="Nama Layanan" placeholder="Contoh: Poli Umum" required />

                <flux:input wire:model="code" label="Kode Layanan" placeholder="Contoh: A" maxlength="10" required />

                <flux:checkbox wire:model="is_active" label="Aktifkan layanan" />

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
