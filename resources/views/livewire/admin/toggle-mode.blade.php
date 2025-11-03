<div class="flex items-center space-x-2">
    <!-- Using a conceptual Tailwind class based on your custom colors -->
    <span class="text-sm font-medium text-gray-500">Mode Pencatatan</span>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox"
               class="sr-only peer"
               wire:model.live="mode" 
        >
        <!-- The visual component now uses the Livewire state ($mode) -->
        <div class="w-9 h-5 rounded-full transition-colors duration-200 relative
            {{ $mode ? 'bg-[var(--color-green)]' : 'bg-gray-300' }}">
            <div class="absolute top-[2px] left-[2px] h-4 w-4 rounded-full bg-white transition-transform duration-200"
                 style="transform: translateX({{ $mode ? '20px' : '0' }});"></div>
        </div>
    </label>
</div>