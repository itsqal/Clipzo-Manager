@extends('layouts.admin')

@section('content')
    <div class="flex flex-col h-[calc(100vh-3rem)]"> 
        <header class="flex justify-between items-center mb-6 shrink-0">
            <x-date-display :month-date="$monthDate" :day-indo="$dayIndo" />

            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gray-300 rounded-lg flex items-center justify-center text-clipzo-text-gray">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="font-semibold text-clipzo-dark">Barbershop Admin</p>
                    <p class="text-sm text-clipzo-text-gray">{{ $userLocation }}</p>
                </div>
            </div>
        </header>

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

        <!-- Main Transaction Grid -->
        <div class="grid grid-cols-12 gap-6 flex-grow min-h-0">

            <div class="col-span-8 flex flex-col space-y-6 min-h-0">

                <!-- Row 1: KPI and Action Button -->
                <div class="flex items-start space-x-6 shrink-0">
                    <livewire:admin.kpi-card />

                    <div class="flex-grow flex items-end">
                        <livewire:admin.toggle-mode />
                    </div>
                </div>

                <!-- Row 2: Service and Product Selection Cards -->
                <div class="grid grid-cols-2 gap-6 flex-grow min-h-0">
                    
                    <livewire:admin.service-selector />
                    
                    <livewire:admin.product-selector />
                </div>

            </div>

            <livewire:admin.transaction-summary />
        </div>
    </div>
@endsection