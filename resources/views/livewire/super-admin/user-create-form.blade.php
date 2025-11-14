<div class="col-span-4 flex flex-col space-y-4 min-h-0">
    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 flex-grow min-h-0 flex flex-col">
        <h3 class="text-base font-semibold text-clipzo-dark mb-3 shrink-0">Tambah Admin Cabang</h3>

        <form wire:submit.prevent="create" class="space-y-3 flex-grow overflow-auto">

            <div>
                <label class="text-xs font-medium text-gray-700 mb-1 block">Username</label>
                <input type="text" 
                       wire:model.defer="username"
                       placeholder="Masukkan username" 
                       class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1" />
                @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="text-xs font-medium text-gray-700 mb-1 block">Password</label>
                <input type="password" 
                       wire:model.defer="password"
                       placeholder="Masukkan password" 
                       class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1" />
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs font-medium text-gray-700 mb-1 block">Konfirmasi Password</label>
                <input type="password"
                       wire:model.defer="password_confirmation"
                       placeholder="Masukkan ulang password"
                       class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1" />
                @error('password_confirmation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="text-xs font-medium text-gray-700 mb-1 block">Lokasi Cabang</label>
                <input type="text" 
                       wire:model.defer="branch_location"
                       placeholder="Contoh: Dayeuh Kolot" 
                       class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1" />
                @error('branch_location') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="text-xs font-medium text-gray-700 mb-1 block">Alamat Lengkap</label>
                <textarea wire:model.defer="address"
                          placeholder="Masukkan alamat lengkap cabang" 
                          rows="3"
                          class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1"></textarea>
                @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex space-x-2 mt-4 shrink-0">
                <button type="submit" class="flex-1 bg-black text-white text-sm font-semibold py-2 rounded-lg shadow hover:bg-gray-800 transition">
                    Simpan
                </button>
                <button type="button" wire:click="resetForm" class="px-4 bg-gray-200 text-gray-700 text-sm font-semibold py-2 rounded-lg hover:bg-gray-300 transition">
                    Reset
                </button>
            </div>

        </form>
    </div>
</div>