@extends('layouts.superadmin')

@section('content')
    <div class="flex flex-col h-[calc(100vh-3rem)]"> 
        <livewire:super-admin.text-chart />
        
        {{-- Main Content Grid --}}
        <div class="flex-grow min-h-0 flex flex-col">
            <div class="flex justify-between items-center mb-4 shrink-0">
                <h2 class="text-xl font-semibold text-clipzo-dark">Manajemen Layanan</h2>
                <div class="flex items-center space-x-2">
                </div>
            </div>

            <div class="grid sm:grid-cols-1 md:grid-cols-12 gap-4 flex-grow min-h-0">

                <!-- Left Section: Table & Chart (8 columns on md+) -->
                <div class="md:col-span-8 col-span-1 flex flex-col space-y-4 min-h-0">
                <!-- Table Widget -->
                <livewire:super-admin.service-table />

                <!-- Chart Widget -->
                <livewire:super-admin.service-chart />
                </div>

                <!-- Right Section: Form & Stats (4 columns on md+) -->
                <div class="md:col-span-4 col-span-1 flex flex-col space-y-4 min-h-full">
                    <!-- Add Service Form -->
                    <div>
                        <livewire:super-admin.service-create-form />
                    </div>
                    
                    <div>
                        <livewire:super-admin.service-stats />
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection