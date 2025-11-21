<div class="bg-white rounded-xl shadow-md border border-gray-200 flex flex-col min-h-0 p-3 md:p-4 max-h-[65vh] md:max-h-none">
    <h3 class="font-semibold text-clipzo-dark mb-2 md:mb-3 md:text-base text-sm shrink-0">Tambah Layanan Baru</h3>

    <form wire:submit.prevent="save" class="space-y-3 flex-grow overflow-auto min-h-0">
        <div>
            <label class="text-xs md:text-sm font-medium text-gray-700 mb-1 block">Nama Layanan</label>
            <input type="text"
                   wire:model.defer="name"
                   placeholder="Contoh: Hair Perming"
                   class="w-full text-xs md:text-sm border-gray-300 border px-1 md:px-2 rounded-lg focus:ring-2 focus:ring-gray-200 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs md:text-sm font-medium text-gray-700 mb-1 block">Deskripsi</label>
            <textarea wire:model.defer="description"
                      placeholder="Deskripsi layanan... (optional)"
                      rows="2"
                      class="w-full text-xs md:text-sm border-gray-300 border px-1 md:px-2 rounded-lg focus:ring-2 focus:ring-gray-200 @error('description') border-red-500 @enderror"></textarea>
            @error('description')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-xs md:text-sm font-medium text-gray-700 mb-1 block">Harga</label>
            <div class="relative">
                <span class="absolute left-3 md:left-4 top-1/2 -translate-y-1/2 text-xs md:text-sm text-gray-500">Rp</span>
                <input type="number"
                       wire:model.defer="price"
                       placeholder="35000"
                       class="w-full text-xs md:text-sm border-gray-300 border pl-8 md:pl-10 px-1 md:px-2 rounded-lg focus:ring-2 focus:ring-gray-200 @error('price') border-red-500 @enderror">
            </div>
            @error('price')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </form>

    <div class="flex space-x-2 mt-3 md:mt-4 shrink-0">
        <button wire:click="save"
                class="flex-1 bg-black text-white text-xs md:text-sm font-semibold py-2 rounded-lg shadow hover:bg-gray-800 transition">
            Simpan Layanan
        </button>
        <button type="button"
                wire:click="resetForm"
                class="px-3 md:px-4 bg-gray-200 text-gray-700 text-xs md:text-sm font-semibold py-2 rounded-lg hover:bg-gray-300 transition">
            Reset
        </button>
    </div>
</div>