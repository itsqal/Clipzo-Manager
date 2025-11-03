<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;

class KpiCard extends Component
{
    protected $listeners = ['transactionFinished' => '$refresh'];

    public $userId;

    public function mount()
    {
        $this->userId = auth()->id();
    }

    public function render()
    {
        $totalTransactions = Transaction::where('user_id', $this->userId)
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->count();;

        return view('livewire.admin.kpi-card', [
            'totalTransactions' => $totalTransactions,
        ]);
    }
}