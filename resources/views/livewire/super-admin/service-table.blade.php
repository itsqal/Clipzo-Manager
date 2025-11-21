<div class="bg-white rounded-xl shadow-md border border-gray-200 flex flex-col flex-1 min-h-[300px] max-h-[70vh] md:max-h-full">
    <div class="p-4 border-b border-gray-200 shrink-0 flex justify-between items-center">
        <h3 class="text-base font-semibold text-clipzo-dark">Daftar Layanan</h3>
        <span class="text-xs">Menampilkan {{ $services->firstItem() ?? 0 }}-{{ $services->lastItem() ?? 0 }} dari {{ $services->total() }} layanan</span>
    </div>

    <div 
        x-data="{ show: false, message: '', type: '' }"
        x-on:flash-message.window="
            message = $event.detail[0].message;
            type = $event.detail[0].type;
            show = true;
            setTimeout(() => show = false, 3000);
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="mx-4 mt-3 mb-3 flex items-center gap-2 rounded-lg border px-3 py-2 text-xs font-medium shadow-sm"
        :class="{
            'bg-green-50 border-green-300 text-green-700': type === 'create',
            'bg-blue-50 border-blue-300 text-blue-700': type === 'update',
            'bg-red-50 border-red-300 text-red-700': type === 'delete',
        }"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="{
            'text-green-600': type === 'create',
            'text-blue-600': type === 'update',
            'text-red-600': type === 'delete'
        }" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414L9 14.414 5.293 10.707a1 1 0 111.414-1.414L9 11.586l6.293-6.293a1 1 0 011.414 0z"
                clip-rule="evenodd" />
        </svg>
        <span x-text="message"></span>
    </div>
    
    <div class="flex-grow overflow-auto min-h-0">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Nomor</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Nama Layanan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Deskripsi</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Harga</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($services as $service)
                    <tr wire:key="service-{{ $service->id }}" 
                        class="hover:bg-gray-50 transition cursor-pointer" 
                        wire:click="viewEditService({{ $service->id }})">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium text-center">{{ ucfirst($service->name) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $service->description }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-semibold text-gray-900">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                            <button class="text-red-600 hover:text-red-800 font-medium cursor-pointer hover:underline"
                                    wire:click.stop="viewDeleteService({{ $service->id }})">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                            Tidak ada data layanan ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-t border-gray-200 shrink-0 flex justify-end items-center">
        <div class="flex space-x-1">
            {{ $services->links() }}
        </div>
    </div>

    <x-modal name="delete-service-modal" title="Hapus Data Layanan">
        @if ($selectedService)
            <p class="font-regular mb-2">Apakah anda yakin ingin menghapus layanan ini?</p>
            <div class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-2">
                <p class="text-sm font-medium text-gray-800">Nama Layanan</p>
                <p class="text-sm font-medium text-gray-800">:&nbsp;&nbsp;&nbsp;{{ $selectedService->name }}</p>

                <p class="text-sm font-medium text-gray-800">Harga</p>
                <p class="text-sm font-medium text-gray-800">:&nbsp;&nbsp;&nbsp;{{ $selectedService->price }}</p>
            </div>

            <div class="flex justify-end mt-4 gap-1">
                <button @click="$dispatch('close-modal')"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-sans font-medium transition cursor-pointer bg-[var(--color-blue)] text-white">
                    Kembali
                <button>
                <button wire:click="deleteService"
                    class="bg-[#C30010] text-xs text-white px-4 py-2 rounded-lg hover:opacity-90 transition cursor-pointer">
                    Hapus
                </button>
            </div>
        @endif
    </x-modal>

    <x-modal name="edit-service-modal" title="Edit Data Layanan">
        @if ($selectedService)
            <form wire:submit.prevent="updateService" class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_service_name" class="text-xs font-medium text-gray-700 mb-1 block">Nama Layanan</label>
                        <input type="text" 
                            id="edit_service_name" 
                            wire:model.defer="service_name"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                        @error('service_name') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="edit_amount" class="text-xs font-medium text-gray-700 mb-1 block">Harga</label>
                        <input type="number" 
                            id="edit_amount" 
                            wire:model.defer="service_price"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                        @error('service_price') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="edit_description" class="text-xs font-medium text-gray-700 mb-1 block">Deskripsi</label>
                    <textarea id="edit_description" 
                            wire:model.defer="service_description" 
                            rows="3"
                            placeholder="Opsional..."
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1"></textarea>
                    @error('service_description') 
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="flex space-x-2 pt-1">
                    <button type="submit"
                            class="flex-1 bg-black text-white text-sm font-semibold py-2 rounded-lg shadow hover:bg-gray-800 transition mt-5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        @else
            <div>{{ $services }}</div>
        @endif
    </x-modal>
</div>