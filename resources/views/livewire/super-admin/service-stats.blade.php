<div class="bg-gradient-to-br bg-black p-4 rounded-xl shadow-lg text-white shrink-0">
    <h4 class="text-xs font-medium mb-3">Statistik Layanan</h4>
    <div class="space-y-3">
        <div class="flex justify-between items-center">
            <span class="text-xs">Total Layanan</span>
            <span class="text-lg font-bold">{{ $serviceCount }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-xs">Harga Tertinggi</span>
            <span class="text-lg font-bold">
                Rp {{ number_format($highestPrice ?? 0, 0, ',', '.') }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-xs">Terpopuler</span>
            <span class="text-xs font-semibold text-green-400">
                {{ $mostPopular }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-xs">Transaksi Bulan Ini</span>
            <span class="text-lg font-bold text-blue-400">
                {{ $totalTransactions }}
            </span>
        </div>
    </div>
</div>