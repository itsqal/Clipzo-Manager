<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Expense;

class TextChart extends Component
{
    public $monthlyAmountTransactions = 0;
    public $monthlyNetProfit = 0;
    public $monthlyExpenses = 0;
    public $busiestHour = [];
    public $monthDate;
    public $dayIndo;
    protected $listeners = ['expenseUpdated' => '$refresh'];

    public function getBusyHour(): array
    {
        $currentMonth = Carbon::now();
        $startDate = $currentMonth->copy()->startOfMonth();
        $endDate = $currentMonth->copy()->endOfMonth();

        $timeSlots = [
            ['start' => '09:00:00', 'end' => '11:00:00', 'label' => '9:00 - 11:00'],
            ['start' => '11:00:00', 'end' => '13:00:00', 'label' => '11:00 - 13:00'],
            ['start' => '13:00:00', 'end' => '15:00:00', 'label' => '13:00 - 15:00'],
            ['start' => '15:00:00', 'end' => '17:00:00', 'label' => '15:00 - 17:00'],
            ['start' => '17:00:00', 'end' => '19:00:00', 'label' => '17:00 - 19:00'],
            ['start' => '19:00:00', 'end' => '21:00:00', 'label' => '19:00 - 21:00'],
        ];

        $busiestSlot = [
            'label' => 'N/A',
            'count' => 0,
            'time' => 'N/A',
        ];

        foreach ($timeSlots as $slot) {
            $count = Transaction::whereBetween('created_at', [$startDate, $endDate])
                ->whereRaw("CAST(created_at AS time) >= ?::time", [$slot['start']])
                ->whereRaw("CAST(created_at AS time) < ?::time", [$slot['end']])
                ->count();

            if ($count > $busiestSlot['count']) {
                $busiestSlot['count'] = $count;
                $busiestSlot['label'] = $slot['label'];
                $busiestSlot['time'] = $slot['label'];
            }
        }

        if ($busiestSlot['time'] !== 'N/A') {
            $timeParts = explode(' - ', $busiestSlot['time']);
            if (count($timeParts) == 2) {
                $startTime = Carbon::createFromFormat('G:i', trim($timeParts[0]));
                $endTime = Carbon::createFromFormat('G:i', trim($timeParts[1]));
                $busiestSlot['time'] = $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
            }
        }

        return $busiestSlot;
    }

    public function getMonthlyStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $currentIncome = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');
        $currentExpense = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        $currentNetProfit = $currentIncome - $currentExpense;

        $prevMonthStart = $now->copy()->subMonth()->startOfMonth();
        $prevMonthEnd = $now->copy()->subMonth()->endOfMonth();

        $previousIncome = Transaction::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->sum('total_amount');
        $previousExpense = Expense::whereBetween('expense_date', [$prevMonthStart, $prevMonthEnd])
            ->sum('amount');
        $previousNetProfit = $previousIncome - $previousExpense;

        $changes = [
            'amount' => $previousIncome > 0 
                ? round((($currentIncome - $previousIncome) / $previousIncome) * 100, 1)
                : null,
            'expenses' => $previousExpense > 0 
                ? round((($currentExpense - $previousExpense) / $previousExpense) * 100, 1)
                : null,
            'netProfit' => $previousNetProfit != 0 
                ? round((($currentNetProfit - $previousNetProfit) / abs($previousNetProfit)) * 100, 1)
                : null,
        ];

        return [
            'current' => [
                'income' => $currentIncome,
                'expenses' => $currentExpense,
                'netProfit' => $currentNetProfit,
            ],
            'changes' => $changes,
        ];
    }

    public function render()
    {
        Carbon::setLocale('id');
        $now = Carbon::now();
        $this->monthDate = $now->translatedFormat('F j');
        $this->dayIndo = $now->translatedFormat('l');

        $stats = $this->getMonthlyStats();

        $this->monthlyAmountTransactions = $stats['current']['income'];
        $this->monthlyExpenses = $stats['current']['expenses'];
        $this->monthlyNetProfit = $stats['current']['netProfit'];
        $this->busiestHour = $this->getBusyHour();

        return view('livewire.super-admin.text-chart')->with([
            'amountChange' => $stats['changes']['amount'],
            'expenseChange' => $stats['changes']['expenses'],
            'netProfitChange' => $stats['changes']['netProfit'],
        ]);
    }
}