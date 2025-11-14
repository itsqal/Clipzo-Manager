<div class="col-span-8 bg-white rounded-xl shadow-md border border-gray-200 flex flex-col min-h-0">
    <div class="p-4 border-b border-gray-200 shrink-0 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-clipzo-dark">Manajemen Admin Barbershop</h3>
        <div class="flex items-center space-x-2 text-xs text-gray-600">
            <span>Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} akun admin</span>
        </div>
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
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Lokasi Cabang</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Alamat</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition cursor-pointer" wire:click="viewOnEditUser('{{ $user->id }}')">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center"># {{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium text-center">{{ ucfirst($user->branch_location) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->address }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                Beroperasi
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                            <button class="text-red-600 hover:text-red-800 font-medium cursor-pointer hover:underline" wire:click.stop="viewOnDeleteUser('{{ $user->id }}')">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                            Tidak ada akun admin cabang ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-t border-gray-200 shrink-0 flex justify-between items-center">
        <div class="flex space-x-1">
            {{ $users->links() }}
        </div>
    </div>

    <x-modal name="delete-user-modal" title="Hapus Akun Admin Cabang">
        @if ($selectedUser)
            <p class="font-regular mb-2">Apakah anda yakin ingin menghapus akun cabang ini?</p>
            <div class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-2">
                <p class="text-sm font-medium text-gray-800">Lokasi Cabang</p>
                <p class="text-sm font-medium text-gray-800">:&nbsp;&nbsp;&nbsp;{{ $selectedUser->branch_location }}</p>

                <p class="text-sm font-medium text-gray-800">Alamat Lengkap</p>
                <p class="text-sm font-medium text-gray-800">:&nbsp;&nbsp;&nbsp;{{ $selectedUser->address }}</p>
            </div>

            <div class="flex justify-end mt-4 gap-1">
                <button @click="$dispatch('close-modal')"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-sans font-medium transition cursor-pointer bg-[var(--color-blue)] text-white">
                    Kembali
                <button>
                <button wire:click="deleteUser"
                    class="bg-[#C30010] text-xs text-white px-4 py-2 rounded-lg hover:opacity-90 transition cursor-pointer">
                    Hapus
                </button>
            </div>
        @endif
    </x-modal>

    <x-modal name="edit-user-modal" title="Edit Data Admin Cabang">
        @if ($selectedUser)
            <form wire:submit.prevent="updateUser" class="space-y-3">
                <div>
                    <label for="username" class="text-xs font-medium text-gray-700 mb-1 block">Username</label>
                    <input type="text"
                        id="username"
                        wire:model.defer="username"
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border"
                        required>
                    @error('username')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="text-xs font-medium text-gray-700 mb-1 block">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password"
                        id="password"
                        wire:model.defer="password"
                        placeholder="••••••••"
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border">
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="branch_location" class="text-xs font-medium text-gray-700 mb-1 block">Lokasi Cabang</label>
                    <input type="text"
                        id="branch_location"
                        wire:model.defer="branch_location"
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border"
                        placeholder="Contoh: Jakarta Selatan">
                    @error('branch_location')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="text-xs font-medium text-gray-700 mb-1 block">Alamat</label>
                    <textarea id="address"
                        wire:model.defer="address"
                        rows="3"
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border"
                        placeholder="Masukkan alamat cabang..."></textarea>
                    @error('address')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-2 pt-1">
                    <button type="submit"
                        class="flex-1 bg-black text-white text-sm font-semibold py-2 rounded-lg shadow hover:bg-gray-800 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        @endif
    </x-modal>
</div>