<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class TransactionTable extends Component
{
    use WithPagination;

    // Filters
    public $startDate;
    public $endDate;
    public $transactionType = 'all';
    public $branchId = 'all';
    public $perPage = 5;

    // For total filtered amount
    public $filteredTotal = 0;
    public $filteredCount = 0;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'transactionType' => ['except' => 'all'],
        'branchId' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function updatingTransactionType()
    {
        $this->resetPage();
    }

    public function updatingBranchId()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->transactionType = 'all';
        $this->branchId = 'all';
        $this->resetPage();
    }

    public function exportToExcel()
    {
        return Excel::download(new TransactionsExport($this->startDate, $this->endDate, $this->transactionType, $this->branchId), 'pemasukan-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function getBaseQuery()
    {
        $query = Transaction::with(['user', 'items.service', 'items.product']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->transactionType !== 'all') {
            $query->where('transaction_type', $this->transactionType);
        }

        if ($this->branchId !== 'all') {
            $query->where('user_id', $this->branchId);
        }

        return $query;
    }

    public function getTransactions()
    {
        return $this->getBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    public function getFilteredStats()
    {
        $query = $this->getBaseQuery();
        
        $this->filteredTotal = $query->sum('total_amount');
        $this->filteredCount = $query->count();
    }

    public function getTransactionTypeLabel($type)
    {
        return match($type) {
            'service' => 'Layanan',
            'product' => 'Produk',
            'both' => 'Layanan + Produk',
            default => $type
        };
    }

    public function getTransactionTypeBadgeClass($type)
    {
        return match($type) {
            'service' => 'bg-blue-100 text-blue-700',
            'product' => 'bg-green-100 text-green-700',
            'both' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700'
        };
    }

    public function render()
    {
        $this->getFilteredStats();
        
        $transactions = $this->getTransactions();
        
        $branches = \App\Models\User::whereNotNull(
            'branch_location')
            ->select('id', 'branch_location')
            ->distinct()
            ->get();

        return view('livewire.super-admin.transaction-table', [
            'transactions' => $transactions,
            'branches' => $branches
        ]);
    }
}