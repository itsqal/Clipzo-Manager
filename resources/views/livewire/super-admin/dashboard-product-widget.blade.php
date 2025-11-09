<div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 h-full flex flex-col">
    <h2 class="text-base font-semibold mb-3 text-clipzo-dark shrink-0">Produk</h2>
    
    <div class="flex-1 overflow-y-auto min-h-0 pr-2">
        <ul class="text-xs space-y-2 text-clipzo-text-gray">
            @foreach ($visibleProducts as $product)
                <li class="flex items-center">
                    <span class="w-1.5 h-1.5 bg-black rounded-full mr-2 shrink-0"></span> 
                    {{ $product->name }}
                </li>
            @endforeach
        </ul>
    </div>

    @if ($products->count() > $limit)
        <div class="mt-3 flex justify-end shrink-0">
            <button wire:click="nextBatch" 
                    class="text-xs font-medium text-blue-600 hover:text-blue-800 cursor-pointer">
                Tampilkan lainnya
            </button>
        </div>
    @endif
</div>