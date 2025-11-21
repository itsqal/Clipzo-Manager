<div class="bg-gradient-to-br bg-black p-3 md:p-4 rounded-xl shadow-lg text-white">
    <h4 class="font-medium md:text-xs text-[0.85rem] mb-2 md:mb-3">Statistik Produk</h4>
    <div class="space-y-2 md:space-y-3">
        <div class="flex justify-between items-center">
            <span class="md:text-xs text-[0.85rem]">Total Produk</span>
            <span class="md:text-lg text-base font-bold">{{ $productCount }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="md:text-xs text-[0.85rem]">Harga Tertinggi</span>
            <span class="md:text-lg text-base font-bold">
                Rp {{ number_format($highestPrice ?? 0, 0, ',', '.') }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="md:text-xs text-[0.85rem]">Terpopuler</span>
            <span class="md:text-xs text-[0.9rem] font-semibold text-green-300">
                {{ $mostPopular }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="md:text-xs text-[0.85rem]">Transaksi Bulan Ini</span>
            <span class="md:text-lg text-base font-bold text-blue-300">
                {{ $totalTransactions }}
            </span>
        </div>
    </div>
</div>