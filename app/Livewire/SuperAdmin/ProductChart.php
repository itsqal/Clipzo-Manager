<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductChart extends Component
{
    public $chartData = [];
    public $period = 'current_month';
    public $limit = 6;

    public function mount()
    {
        $this->loadChartData();
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $dateRange = $this->getDateRange();
        
        $topProducts = Product::select(
                'products.id',
                'products.name',
                DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_sold')
            )
            ->leftJoin('transaction_items', function($join) use ($dateRange) {
                $join->on('products.id', '=', 'transaction_items.product_id')
                     ->where('transaction_items.item_type', 'product')
                     ->whereBetween('transaction_items.created_at', $dateRange);
            })
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($this->limit)
            ->get();

        $this->chartData = [
            'labels' => $topProducts->pluck('name')->toArray(),
            'data' => $topProducts->pluck('total_sold')->map(fn($val) => (int)$val)->toArray(),
        ];
    }

    private function getDateRange()
    {
        $now = Carbon::now();
        
        return match($this->period) {
            'current_month' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth()
            ],
            '3_months' => [
                $now->copy()->subMonths(3)->startOfMonth(),
                $now->copy()->endOfMonth()
            ],
            '6_months' => [
                $now->copy()->subMonths(6)->startOfMonth(),
                $now->copy()->endOfMonth()
            ],
            'year' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear()
            ],
            default => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth()
            ]
        };
    }

    public function getPeriodLabel()
    {
        return match($this->period) {
            'current_month' => 'Bulan Ini',
            '3_months' => '3 Bulan Terakhir',
            '6_months' => '6 Bulan Terakhir',
            'year' => 'Tahun Ini',
            default => 'Bulan Ini'
        };
    }

    public function render()
    {
        return view('livewire.super-admin.product-chart');
    }
}