<div class="bg-white p-6 md:p-5 sm:p-4 rounded-xl shadow-md border border-gray-200 flex flex-col min-h-0">
    <h2 class="text-xl md:text-lg sm:text-base font-semibold mb-4 text-clipzo-dark shrink-0 text-center sm:text-left">
        Pilih Jenis Layanan
    </h2>
    
    <!-- SCROLLABLE CONTENT -->
    <div class="flex flex-wrap gap-4 sm:gap-3 overflow-y-auto pr-2 flex-grow content-start">
        @foreach ($services as $service)
            @php
                $isSelected = in_array($service['id'], array_column($selectedServices, 'id'));

                $buttonClasses = 'text-sm md:text-xs sm:text-[11px] p-4 md:p-3 sm:p-2 rounded-lg font-medium transition-colors border border-gray-300 h-fit text-center w-[calc(50%-0.5rem)] sm:w-full';

                if (!$mode) {
                    $buttonClasses .= ' bg-gray-100 text-gray-400 opacity-60 pointer-events-none';
                } elseif ($isSelected) {
                    $buttonClasses .= ' bg-green-500 text-white shadow-lg border-green-600 cursor-pointer';
                } else {
                    $buttonClasses .= ' bg-black text-white hover:opacity-80 cursor-pointer';
                }
            @endphp
            
            <button 
                class="{{ $buttonClasses }}"
                wire:click="selectService({{ $service['id'] }}, '{{ $service['name'] }}', {{ $service['price'] }})"
                @if (!$mode) disabled @endif
            >
                <span class="block font-semibold truncate">{{ $service['name'] }}</span>
                <span class="text-xs md:text-[11px] sm:text-[10px] font-light">
                    (Rp {{ number_format($service['price'], 0, ',', '.') }})
                </span>
            </button>
        @endforeach
    </div>
</div>