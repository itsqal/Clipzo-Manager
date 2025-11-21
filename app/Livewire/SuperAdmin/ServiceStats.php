<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Transaction;
use Livewire\Attributes\On;

class ServiceStats extends Component
{
    public $startDate;
    public $endDate;

    #[On('service-updated')] 
    public function refreshStats()
    {

    }

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function getBaseQuery()
    {
        $query = Service::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query;
    }

    public function render()
    {
        $query = $this->getBaseQuery();

        $serviceCount = $query->count();

        $highestPrice = $query->max('price');

        $mostPopular = Service::withCount(['transactionItems as monthly_sales_count' => function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }])
        ->orderByDesc('monthly_sales_count')
        ->first();

        $totalTransactions = Transaction::whereIn('transaction_type', ['service', 'both'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        return view('livewire.super-admin.service-stats', [
            'serviceCount' => $serviceCount,
            'highestPrice' => $highestPrice,
            'mostPopular' => ($mostPopular && $mostPopular->monthly_sales_count > 0)
                ? $mostPopular->name
                : '-',
            'totalTransactions' => $totalTransactions,
        ]);
    }
}
