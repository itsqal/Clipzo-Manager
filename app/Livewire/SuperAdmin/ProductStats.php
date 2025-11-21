<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use Livewire\Attributes\On; 

class ProductStats extends Component
{
    public $startDate;
    public $endDate;

    #[On('product-updated')] 
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
        $query = Product::query();

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

        $productCount = $query->count();

        $highestPrice = $query->max('price');

        $mostPopular = Product::withCount(['transactionItems as monthly_sales_count' => function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }])
        ->orderByDesc('monthly_sales_count')
        ->first();

        $totalTransactions = Transaction::whereIn('transaction_type', ['product', 'both'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        return view('livewire.super-admin.product-stats', [
            'productCount' => $productCount,
            'highestPrice' => $highestPrice,
            'mostPopular' => ($mostPopular && $mostPopular->monthly_sales_count > 0)
                ? $mostPopular->name
                : '-',
            'totalTransactions' => $totalTransactions,
        ]);
    }
}
