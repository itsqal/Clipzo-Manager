<div class="col-span-4 bg-white p-6 md:p-5 sm:p-4 rounded-xl shadow-md border border-gray-200 flex flex-col">
    <h2 class="text-xl md:text-lg sm:text-base font-semibold mb-4 shrink-0">Ringkasan Transaksi</h2>

    <div class="flex-grow overflow-y-auto pr-2 min-h-0">
        @php
            $allItems = array_merge($selectedServices, $selectedProducts);
        @endphp

        @if (count($allItems) > 0)
            <ul class="space-y-3 sm:space-y-2">
                @foreach ($allItems as $item)
                    @php
                        $label = ($item['type'] === 'service') ? 'Layanan' : 'Produk';
                    @endphp
                    <li class="grid grid-cols-[1fr_auto_auto] items-center gap-3 py-2 border-b border-gray-100 text-sm sm:text-xs">
                        <div class="flex flex-col truncate">
                            <span class="font-medium truncate">{{ $item['name'] }}</span>
                            <span class="text-xs text-green-500">{{ $label }}</span>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-1 justify-end">
                            <button 
                                wire:click="decrementQuantity({{ $item['id'] }}, '{{ $item['type'] }}')" 
                                class="px-2 sm:px-1.5 rounded bg-gray-200 text-gray-700 font-bold disabled:opacity-50"
                                @if($item['quantity'] <= 1) disabled @endif
                            >-</button>
                            <span class="px-2 sm:px-1 font-semibold">{{ $item['quantity'] ?? 1 }}</span>
                            <button 
                                wire:click="incrementQuantity({{ $item['id'] }}, '{{ $item['type'] }}')" 
                                class="px-2 sm:px-1.5 rounded bg-gray-200 text-gray-700 font-bold"
                            >+</button>
                        </div>

                        <div class="text-right font-semibold whitespace-nowrap">
                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="flex items-center justify-center text-center text-[var(--color-text-gray)] h-full">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-sm sm:text-xs">Belum ada produk/layanan dipilih</p>
                </div>
            </div>
        @endif
    </div>
    
    <span class="mt-4 text-sm md:text-xs font-medium shrink-0">Metode Pembayaran</span>
    <div class="flex gap-2 mt-1 shrink-0 flax-wrap flex-col lg:flex-row">
        @php
            $methods = ['Tunai', 'QRIS', 'Lainnya'];
        @endphp
        @foreach ($methods as $method)
            <button 
                wire:click="setPaymentMethod('{{ $method }}')"
                class="text-white font-regular py-2 md:py-1.5 sm:py-1 px-4 md:px-3 sm:px-2 rounded-lg shadow-lg w-full sm:w-full transition-all duration-150
                    {{ $total == 0 ? 'bg-gray-400 opacity-50 cursor-not-allowed' : ($paymentMethod === $method ? 'bg-[var(--color-blue)] shadow-xl cursor-pointer' : 'bg-gray-700 cursor-pointer') }} "
                {{ $total == 0 ? 'disabled' : '' }}
            >
                {{ $method }}
            </button>
        @endforeach
    </div>

    @php
        $isCheckoutEnabled = ($total > 0 && $paymentMethod);
        $checkoutClasses = $isCheckoutEnabled ? 'bg-[var(--color-blue)] shadow-xl cursor-pointer' : 'bg-gray-400 opacity-50 cursor-not-allowed';
    @endphp

    <div class="mt-4 pt-4 border-t border-gray-200 shrink-0">
        <div class="flex justify-between font-bold text-lg md:text-base sm:text-sm mb-4">
            <span>TOTAL</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <button 
            wire:click="finishTransaction"
            class="w-full py-3 md:py-2 sm:py-1.5 text-white font-semibold rounded-lg transition-all duration-150 text-base md:text-sm sm:text-xs {{ $checkoutClasses }}"
            {{ $isCheckoutEnabled ? '' : 'disabled' }}
        >
            Selesaikan Transaksi (Rp {{ number_format($total, 0, ',', '.') }})
        </button>

        <button 
            id="printLastTransaction"
            class="w-full mt-3 py-2 md:py-1.5 text-[var(--color-blue)] font-medium rounded-lg border border-[var(--color-blue)] hover:bg-[var(--color-blue)] hover:text-white transition-all duration-150 text-sm md:text-xs sm:text-[11px]"
        >
            Cetak Transaksi Terakhir
        </button>
    </div>
</div>

@script
<script>
window.addEventListener('print-transaction', (event) => {
    try {
        const data = event.detail[0] || event.detail;
        if (!data) throw new Error('Data transaksi tidak ditemukan.');

        const items = data.items.map(
            i => `${i.name} (${i.quantity}x) - Rp ${i.subtotal.toLocaleString('id-ID')}`
        ).join('\n');

        const text = `
        CLIPZO BARBERSHOP
        --------------------------
        Cabang : ${data.branch}
        ${data.time}
        --------------------------
        ${items}
        --------------------------
        Total   : Rp ${data.total.toLocaleString('id-ID')}
        Bayar   : ${data.payment_method}
        --------------------------
        Terima kasih!
        `;

        const encodedText = encodeURIComponent(text);
        window.location.href = `rawbt:=${encodedText}`;
    } catch (error) {
        console.error('Gagal mencetak transaksi:', error);
        alert('Terjadi kesalahan saat mencetak transaksi. Pastikan printer RawBT sudah siap.');
    }
});

document.getElementById('printLastTransaction').addEventListener('click', async () => {
    try {
        const res = await fetch('/api/transactions/last');
        if (!res.ok) throw new Error('Gagal mengambil data transaksi terakhir.');

        const data = await res.json();
        const t = data.data;

        if (!t) throw new Error('Tidak ada transaksi terakhir yang ditemukan.');

        const items = t.items.map(i =>
            `${i.name} (${i.quantity}x) - Rp ${i.subtotal.toLocaleString('id-ID')}`
        ).join('\n');

        const text = `
        CLIPZO BARBERSHOP
        --------------------------
        Cabang : ${t.branch_location}
        ${t.created_at}
        --------------------------
        ${items}
        --------------------------
        Total   : Rp ${t.total_amount.toLocaleString('id-ID')}
        Bayar   : ${t.payment_method}
        --------------------------
        Terima kasih!
                `;

        const encodedText = encodeURIComponent(text.trim());
        window.location.href = `rawbt:=${encodedText}`;
    } catch (error) {
        console.error('Gagal mencetak transaksi terakhir:', error);
        alert('Gagal mencetak transaksi terakhir. Pastikan printer RawBT sudah siap.');
    }
});
</script>
@endscript