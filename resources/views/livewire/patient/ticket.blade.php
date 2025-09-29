<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Ambil Tiket</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="service">
                Pilih Layanan
            </label>
            <select id="service" class="w-full px-3 py-2 border rounded-lg">
                <option>-- Pilih Layanan --</option>
                <option>Poli Umum</option>
                <option>Poli Gigi</option>
                <option>Poli Anak</option>
            </select>
        </div>
        
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Ambil Tiket
        </button>
    </div>
    
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Riwayat Tiket</h2>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi melalui Livewire -->
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Belum ada riwayat tiket
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
