<div>
    <div class="grid grid-cols-3 gap-4 mb-4 shrink-0">
        {{-- Filters Widget (2/3 width) --}}
        <div class="col-span-2 bg-white p-4 rounded-xl shadow-md border border-gray-200">
            <div class="grid grid-cols-4 gap-3 mb-3">
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Tanggal Awal</label>
                    <input type="date" 
                           wire:model.live="startDate"
                           class="w-full text-xs border-gray-400 border-1 px-1 rounded-lg focus:ring-2 focus:ring-gray-200" />
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Tanggal Akhir</label>
                    <input type="date" 
                           wire:model.live="endDate"
                           class="w-full text-xs border-gray-400 border-1 px-1 rounded-lg focus:ring-2 focus:ring-gray-200" />
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Pilih Cabang</label>
                    <select wire:model.live="branchId"
                            class="w-full text-xs border-gray-400 border-1 px-1 rounded-lg focus:ring-2 focus:ring-gray-200">
                        <option value="all">Semua</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <button wire:click="resetFilters" 
                        class="text-xs text-gray-600 hover:text-gray-800 hover:underline font-medium transition">
                    Reset Filter
                </button>
                <button wire:click="exportToExcel"
                        class="bg-green-600 text-white text-xs font-semibold py-2 px-4 rounded-lg shadow hover:bg-green-700 transition">
                    Export ke XLSX
                </button>
            </div>
        </div>

        {{-- Total pengeluaran Widget (1/3 width) --}}
        <div class="bg-black text-white p-4 rounded-xl shadow-lg flex flex-col justify-center">
            <p class="text-xs font-medium opacity-80 mb-1">Total pengeluaran Terfilter</p>
            <h2 class="text-2xl font-bold">Rp {{ number_format($filteredTotal, 0, ',', '.') }}</h2>
            <p class="text-xs opacity-60 mt-1">Dari {{ number_format($filteredCount, 0, ',', '.') }} pengeluaran</p>
        </div>
    </div>

    {{-- Table Widget --}}
    <div class="grid grid-cols-3 gap-4">
        <!-- Kolom 2/3: Data Pengeluaran -->
        <div class="col-span-2 bg-white rounded-xl shadow-md border border-gray-200 flex flex-col">
            <div class="p-4 border-b border-gray-200 shrink-0 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-clipzo-dark">Data Pengeluaran</h3>
                <div class="flex items-center space-x-2 text-xs text-gray-600">
                    <span>Menampilkan {{ $expenses->firstItem() ?? 0 }}-{{ $expenses->lastItem() ?? 0 }} dari {{ $expenses->total() }} pengeluaran</span>
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
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nomor</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Pengeluaran</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cabang</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Item</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition cursor-pointer" wire:click="viewOnEditExpense('{{ $expense->id }}')">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                    {{ $expense->formatted_expense_date }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                    {{ $expense->branch_location ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                    {{ $expense->item_name }}
                                </td>
                                    <td 
                                        class="px-4 py-3 text-sm text-gray-600 text-left max-w-[200px] truncate cursor-pointer" >
                                        {{ $expense->description }}
                                    </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-semibold text-gray-900">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                    <button class="text-red-600 hover:text-red-800 font-medium cursor-pointer hover:underline" wire:click.stop="viewOnDeleteExpense('{{ $expense->id }}')">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Tidak ada pengeluaran ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200 shrink-0 w-full flex justify-end">
                {{ $expenses->links() }}
            </div>
        </div>

        <!-- Kolom 1/3: Widget Kosong -->
        <div class="col-span-1 bg-white rounded-xl shadow-md border border-gray-200 flex flex-col p-4">
            <h3 class="text-lg font-semibold mb-4">Tambah Pengeluaran</h3>

            <form wire:submit.prevent="create" class="space-y-3">
                <div class="max-h-[200px] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="item_name" class="text-xs font-medium text-gray-700 mb-1 block">Nama Item</label>
                            <input type="text" 
                                id="item_name" 
                                wire:model.defer="item_name"
                                placeholder="Masukkan nama item"
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1"
                                required>
                            @error('item_name') 
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
    
                        <div>
                            <label for="amount" class="text-xs font-medium text-gray-700 mb-1 block">Jumlah</label>
                            <input type="number" 
                                id="amount" 
                                wire:model.defer="amount"
                                placeholder="Misal: 100000"
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1"
                                required>
                            @error('amount') 
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
    
                    <div>
                        <label for="description" class="text-xs font-medium text-gray-700 mb-1 block">Deskripsi</label>
                        <textarea id="description" 
                                wire:model.defer="description" 
                                rows="3"
                                placeholder="Opsional..."
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1"></textarea>
                        @error('description') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
    
                    <div>
                        <label class="text-xs font-medium text-gray-700 mb-1 block">Pilih Cabang</label>
                        <select wire:model.defer="user_id"
                                class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                            <option value="">Pilih cabang...</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                            @endforeach
                        </select>
                        @error('user_id') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
    
                    <div>
                        <label for="expense_date" class="text-xs font-medium text-gray-700 mb-1 block">
                            Tanggal Pengeluaran
                        </label>
                        <input type="date"
                            id="expense_date"
                            wire:model.lazy="expense_date"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                        @error('expense_date') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div class="flex space-x-2 pt-1">
                    <button type="submit"
                            class="flex-1 bg-black text-white text-sm font-semibold py-2 rounded-lg shadow hover:bg-gray-800 transition mt-5">
                        Tambah Pengeluaran
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal --}}
    <x-modal name="delete-expense-modal" title="Hapus Data Pengeluaran">
        @if ($selectedExpense)
            <p class="font-regular mb-2">Apakah anda yakin ingin menghapus pengeluaran ini?</p>
            <div class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-2">
                <p class="text-sm font-medium text-gray-800">Nama Item</p>
                <p class="text-sm font-medium text-gray-800">:&nbsp;&nbsp;&nbsp;{{ $selectedExpense->item_name }}</p>

                <p class="text-sm font-medium text-gray-800">Tanggal Pengeluaran</p>
                <p class="text-sm font-medium text-gray-800">:&nbsp;&nbsp;&nbsp;{{ ucwords($selectedExpense->formatted_expense_date) }}</p>
            </div>

            <div class="flex justify-end mt-4 gap-1">
                <button @click="$dispatch('close-modal')"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-sans font-medium transition cursor-pointer bg-[var(--color-blue)] text-white">
                    Kembali
                <button>
                <button wire:click="deleteExpense"
                    class="bg-[#C30010] text-xs text-white px-4 py-2 rounded-lg hover:opacity-90 transition cursor-pointer">
                    Hapus
                </button>
            </div>
        @endif
    </x-modal>

    <x-modal name="edit-expense-modal" title="Edit Data Pengeluaran">
        @if ($selectedExpense)
            <form wire:submit.prevent="updateExpense" class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_item_name" class="text-xs font-medium text-gray-700 mb-1 block">Nama Item</label>
                        <input type="text" 
                            id="edit_item_name" 
                            wire:model.defer="item_name"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                        @error('item_name') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="edit_amount" class="text-xs font-medium text-gray-700 mb-1 block">Jumlah</label>
                        <input type="number" 
                            id="edit_amount" 
                            wire:model.defer="amount"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                        @error('amount') 
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="edit_description" class="text-xs font-medium text-gray-700 mb-1 block">Deskripsi</label>
                    <textarea id="edit_description" 
                            wire:model.defer="description" 
                            rows="3"
                            placeholder="Opsional..."
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1"></textarea>
                    @error('description') 
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Pilih Cabang</label>
                    <select wire:model.defer="user_id"
                            class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                        <option value="">Pilih cabang...</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_location }}</option>
                        @endforeach
                    </select>
                    @error('user_id') 
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="edit_expense_date" class="text-xs font-medium text-gray-700 mb-1 block">Tanggal Pengeluaran</label>
                    <input type="date"
                        id="edit_expense_date"
                        wire:model.defer="expense_date"
                        class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-200 p-1 border-1">
                    @error('expense_date') 
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
        @endif
    </x-modal>
</div>