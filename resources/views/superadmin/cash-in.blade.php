@extends('layouts.superadmin')

@section('content')
<div class="flex flex-col h-[calc(100vh-3rem)]">
    <livewire:super-admin.text-chart />

    <livewire:super-admin.transaction-table />
    </div>
</div>
@endsection