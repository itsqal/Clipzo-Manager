<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class customerChart extends Component
{
    public $chartData = [];
    public $viewMode = 'daily';
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        $this->loadChartData();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        
        switch ($mode) {
            case 'daily':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'weekly':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'monthly':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
        }
        
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate)->endOfDay();

        if ($this->viewMode === 'daily') {
            $this->chartData = $this->getDailyData($start, $end);
        } elseif ($this->viewMode === 'weekly') {
            $this->chartData = $this->getWeeklyData($start, $end);
        } else {
            $this->chartData = $this->getMonthlyData($start, $end);
        }

        $this->dispatch('updateCustomerChart', $this->chartData);
    }

    private function getDailyData($start, $end)
    {
        $dailyCustomer = Transaction::selectRaw("
                DATE(created_at) as date,
                COUNT(*) as total
            ")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function($item) {
                return (int) $item->total;
            });

        $data = [];
        $labels = [];
        $currentDate = $start->copy();
        
        while ($currentDate <= $end) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayName = $currentDate->locale('id')->isoFormat('ddd');
            $labels[] = $dayName;
            $data[] = $dailyCustomer->get($dateStr, 0);
            $currentDate->addDay();
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'title' => 'Pelanggan Harian - Minggu Ini'
        ];
    }

    private function getWeeklyData($start, $end)
    {
        $weeklyCustomer = Transaction::selectRaw("
                EXTRACT(WEEK FROM (created_at)) as week_num,
                COUNT(*) as total
            ")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('week_num')
            ->orderBy('week_num')
            ->get()
            ->pluck('total', 'week_num');

        $data = [];
        $labels = [];
        $currentDate = $start->copy()->startOfWeek();
        $weekNumber = 1;
        
        while ($currentDate <= $end) {
            $weekEnd = $currentDate->copy()->endOfWeek();
            if ($weekEnd > $end) {
                $weekEnd = $end->copy();
            }
            
            $weekNum = $currentDate->week;
            $labels[] = "Minggu {$weekNumber}";
            $data[] = $weeklyCustomer->get($weekNum, 0);
            
            $currentDate->addWeek();
            $weekNumber++;
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'title' => 'Pelanggan Mingguan - ' . $start->translatedFormat('F Y')
        ];
    }

    private function getMonthlyData($start, $end)
    {
        $monthlyCustomer = Transaction::selectRaw("
                EXTRACT(MONTH FROM (created_at)) as month_num,
                COUNT(*) as total
            ")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get()
            ->pluck('total', 'month_num');

        Carbon::setLocale('id');
        
        $data = [];
        $labels = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::create(null, $month, 1)->translatedFormat('M');
            $labels[] = $monthName;
            $data[] = $monthlyCustomer->get($month, 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'title' => 'Pelanggan Bulanan - ' . $start->year
        ];
    }

    public function render()
    {
        return view('livewire.super-admin.customer-chart');
    }
}