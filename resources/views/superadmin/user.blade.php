@extends('layouts.superadmin')

@section('content')
    <div class="flex flex-col h-[calc(100vh-3rem)]"> 
        <livewire:super-admin.text-chart />
        
        {{-- Main Content --}}
        <div class="flex-grow min-h-0 flex flex-col">
            <div class="flex justify-between items-center mb-4 shrink-0">
                <h2 class="text-xl font-semibold text-clipzo-dark">Manajemen Admin Barbershop</h2>
            </div>

            <div class="grid grid-cols-12 gap-4 flex-grow min-h-0">

                {{-- Table Section (8 columns) --}}
                <livewire:super-admin.user-table />

                {{-- Form Section (4 columns) --}}
                <livewire:super-admin.user-create-form />

            </div>
        </div>
    </div>
@endsection