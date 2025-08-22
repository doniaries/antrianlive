<div>
    <flux:header>
        <flux:heading size="xl">Manajemen Antrian</flux:heading>
        <flux:subheading>Sistem pengelolaan antrian pelayanan</flux:subheading>
    </flux:header>

    <flux:main class="space-y-6">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $this->waitingCount }}</div>
                    <div class="text-sm text-gray-600">Menunggu</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $this->calledCount }}</div>
                    <div class="text-sm text-gray-600">Dipanggil</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $this->finishedCount }}</div>
                    <div class="text-sm text-gray-600">Selesai</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <flux:button wire:click="openModal" variant="primary" class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white dark:text-white">
                        <flux:icon.plus class="mr-2" />
                        Tambah Antrian
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <flux:label>Tanggal</flux:label>
                    <flux:input 
                        type="date" 
                        wire:model.live="filterDate" 
                        class="mt-1"
                    />
                </div>
                <div>
                    <flux:label>Layanan</flux:label>
                    <flux:select wire:model.live="filterService" class="mt-1">
                        <option value="">Semua Layanan</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model.live="filterStatus" class="mt-1">
                        <option value="">Semua Status</option>
                        <option value="waiting">Menunggu</option>
                        <option value="called">Dipanggil</option>
                        <option value="finished">Selesai</option>
                        <option value="skipped">Dilewati</option>
                    </flux:select>
                </div>
                <div class="flex items-end">
                    <flux:button wire:click="$refresh" variant="ghost" class="w-full">
                        <flux:icon.arrow-path class="mr-2" />
                        Refresh
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Antrians Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Antrian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($antrians as $antrian)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-lg text-gray-900">{{ $antrian->formatted_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium text-sm text-gray-900">{{ $antrian->service->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $antrian->service->code }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($antrian->counter)
                                        {{ $antrian->counter->name }}
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                                        $antrian->status === 'waiting' ? 'bg-blue-100 text-blue-800' :
                                        ($antrian->status === 'called' ? 'bg-yellow-100 text-yellow-800' :
                                        ($antrian->status === 'finished' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))
                                    }}">
                                        {{
                                            $antrian->status === 'waiting' ? 'Menunggu' :
                                            ($antrian->status === 'called' ? 'Dipanggil' :
                                            ($antrian->status === 'finished' ? 'Selesai' : 'Dilewati'))
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-sm">
                                        <div>{{ $antrian->created_at->format('d/m/Y') }}</div>
                                        <div>{{ $antrian->created_at->format('H:i:s') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-1">
                                        @if($antrian->status === 'waiting')
                                            <button 
                                                wire:click="callNext({{ $antrian->service_id }}, {{ $antrian->counter_id ?? 1 }})" 
                                                class="text-gray-400 hover:text-gray-600 p-1"
                                                title="Panggil"
                                            >
                                                <flux:icon.megaphone class="w-4 h-4" />
                                            </button>
                                        @elseif($antrian->status === 'called')
                                            <button 
                                                wire:click="finish({{ $antrian->id }})" 
                                                class="text-green-400 hover:text-green-600 p-1"
                                                title="Selesai"
                                            >
                                                <flux:icon.check class="w-4 h-4" />
                                            </button>
                                            <button 
                                                wire:click="skip({{ $antrian->id }})" 
                                                class="text-orange-400 hover:text-orange-600 p-1"
                                                title="Lewati"
                                            >
                                                <flux:icon.arrow-right class="w-4 h-4" />
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="edit({{ $antrian->id }})" 
                                            class="text-gray-400 hover:text-gray-600 p-1"
                                            title="Edit"
                                        >
                                            <flux:icon.pencil class="w-4 h-4" />
                                        </button>
                                        <button 
                                            wire:click="delete({{ $antrian->id }})" 
                                            class="text-red-400 hover:text-red-600 p-1"
                                            title="Hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus antrian ini?')"
                                        >
                                            <flux:icon.trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <flux:icon.inbox class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                    <div class="text-gray-500">Tidak ada antrian</div>
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
        <flux:modal wire:model="showModal" class="w-full max-w-lg">
            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'create' }}" class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $isEditMode ? 'Edit Antrian' : 'Tambah Antrian' }}
                    </flux:heading>
                </div>

                <div>
                    <flux:label>Layanan</flux:label>
                    <flux:select wire:model="selectedService" required class="mt-1">
                        <option value="">Pilih Layanan</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->code }})</option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:label>Loket (Opsional)</flux:label>
                    <flux:select wire:model="selectedCounter" class="mt-1">
                        <option value="">Pilih Loket</option>
                        @foreach($counters as $counter)
                            <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                @if($isEditMode)
                    <div>
                        <flux:label>Status</flux:label>
                        <flux:select wire:model="status" required class="mt-1">
                            <option value="waiting">Menunggu</option>
                            <option value="called">Dipanggil</option>
                            <option value="finished">Selesai</option>
                            <option value="skipped">Dilewati</option>
                        </flux:select>
                    </div>
                @endif

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
