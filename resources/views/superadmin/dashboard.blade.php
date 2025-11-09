@extends('layouts.superadmin')

@section('content')
    <div class="flex flex-col h-[calc(100vh-3rem)] overflow-hidden"> 
        {{-- KPI Cards --}}
        <livewire:super-admin.text-chart />
        
        {{-- Main Dashboard Scrollable Area --}}
        <div class="flex-grow overflow-y-auto min-h-0 px-2 pb-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 min-h-0">

                {{-- LEFT COLUMN (2/3 width on large screens) --}}
                <div class="lg:col-span-2 flex flex-col space-y-4 min-h-0">
                    {{-- WIDGET 1: PEMASUKAN (REVENUE) CHART --}}
                    <div class="flex-1 min-h-[250px]">
                        <livewire:super-admin.revenue-chart />
                    </div>
                    
                    {{-- WIDGET 2: PELANGGAN (CUSTOMER) CHART --}}
                    <div class="flex-1 min-h-[250px]">
                        <livewire:super-admin.customer-chart />
                    </div>
                </div>

                {{-- RIGHT COLUMN (1/3 width on large screens) --}}
                <div class="lg:col-span-1 flex flex-col space-y-4 min-h-0">
                    
                    {{-- LAYANAN & PRODUK --}}
                    <div class="grid grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4 flex-[3] min-h-0">
                        <div class="min-h-[200px] h-full">
                            <livewire:super-admin.dashboard-service-widget />
                        </div>

                        <div class="min-h-[200px] h-full">
                            <livewire:super-admin.dashboard-product-widget />
                        </div>
                    </div>

                    {{-- AKUN ADMIN --}}
                    <div class="flex-[2] min-h-[200px]">
                        <livewire:super-admin.dashboard-user-widget />
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection