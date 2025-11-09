<div>
    {{-- Filters & Summary Section --}}
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
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Tipe Transaksi</label>
                    <select wire:model.live="transactionType"
                            class="w-full text-xs border-gray-400 border-1 px-1 rounded-lg focus:ring-2 focus:ring-gray-200">
                        <option value="all">Semua</option>
                        <option value="service">Layanan</option>
                        <option value="product">Produk</option>
                        <option value="both">Keduanya</option>
                    </select>
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

        {{-- Total Pendapatan Widget (1/3 width) --}}
        <div class="bg-black text-white p-4 rounded-xl shadow-lg flex flex-col justify-center">
            <p class="text-xs font-medium opacity-80 mb-1">Total Pendapatan Terfilter</p>
            <h2 class="text-2xl font-bold">Rp {{ number_format($filteredTotal, 0, ',', '.') }}</h2>
            <p class="text-xs opacity-60 mt-1">Dari {{ number_format($filteredCount, 0, ',', '.') }} transaksi</p>
        </div>
    </div>

    {{-- Table Widget --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200 flex-grow min-h-0 flex flex-col">
        <div class="p-4 border-b border-gray-200 shrink-0 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-clipzo-dark">Data Transaksi</h3>
            <div class="flex items-center space-x-2 text-xs text-gray-600">
                <span>Menampilkan {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi</span>
            </div>
        </div>
        
        <div class="flex-grow overflow-auto min-h-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nomor</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Item</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Cabang</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-center">{{ $loop->iteration }}</td>
                            
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ $transaction->item }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ $transaction->formatted_date }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ ucfirst($transaction->branch_location) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $this->getTransactionTypeBadgeClass($transaction->transaction_type) }}">
                                    {{ $this->getTransactionTypeLabel($transaction->transaction_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">{{ $transaction->payment_method }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center font-semibold text-gray-900">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                Tidak ada transaksi ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="p-4 border-t border-gray-200 shrink-0 w-full flex justify-end">
            {{ $transactions->links() }}
        </div>
    </div>
</div>