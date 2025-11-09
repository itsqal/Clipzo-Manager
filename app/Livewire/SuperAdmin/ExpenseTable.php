<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpensesExport;
use App\Models\User;

class ExpenseTable extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $branchId = 'all';
    public $perPage = 5;

    public $item_name;
    public $description;
    public $user_id;
    public $amount;
    public $expense_date;

    public $filteredTotal = 0;
    public $filteredCount = 0;  

    public $selectedExpense;
    protected $listeners = ['expenseUpdated' => '$refresh'];

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'user_id' => 'nullable|exists:users,id',
        'amount' => 'required|integer|min:1',
        'expense_date' => 'nullable|date',
    ];

    protected $messages = [
        'item_name.required' => 'Nama item tidak boleh kosong.',
        'amount.required' => 'Jumlah tidak boleh kosong.'
    ];

    public function viewOnDeleteExpense($expenseId)
    {
        $this->selectedExpense = Expense::findOrFail($expenseId);
        $this->dispatch('open-modal', name: 'delete-expense-modal');
    }

    public function viewOnEditExpense($expenseId)
    {
        $this->selectedExpense = Expense::findOrFail($expenseId);

        $this->item_name = $this->selectedExpense->item_name;
        $this->description = $this->selectedExpense->description;
        $this->amount = $this->selectedExpense->amount;
        $this->user_id = $this->selectedExpense->user_id;
        $this->expense_date = $this->selectedExpense->expense_date
            ? Carbon::parse($this->selectedExpense->expense_date)->format('Y-m-d')
            : null;

        $this->dispatch('open-modal', name: 'edit-expense-modal');
    }

    public function deleteExpense()
    {
        $expense = Expense::findOrFail($this->selectedExpense->id);
        $expense->delete();

        $this->dispatch('flash-message', [
            'message' => 'Data pengeluaran berhasil dihapus.',
            'type' => 'delete'
        ]);

        $this->reset('selectedExpense');

        $this->dispatch('expenseUpdated');
        $this->dispatch('close-modal');
    }

    public function updateExpense()
    {
        $this->validate();

        $branch_location = null;

        if ($this->user_id) {
            $user = User::find($this->user_id);
            $branch_location = $user->branch_location;
        }

        if ($this->selectedExpense) {
            $this->selectedExpense->update([
                'item_name' => $this->item_name,
                'branch_location' => $branch_location,
                'description' => $this->description,
                'amount' => $this->amount,
                'user_id' => !empty($this->user_id) ? $this->user_id : null,
                'expense_date' => $this->expense_date ?? Carbon::today(),
            ]);
        }

        $this->dispatch('flash-message', [
            'message' => 'Data pengeluaran berhasil diperbarui.',
            'type' => 'update'
        ]);

        $this->reset(['item_name', 'description', 'amount', 'user_id', 'expense_date', 'selectedExpense']);
        $this->dispatch('close-modal', name: 'edit-expense-modal');
        $this->dispatch('expenseUpdated');          
    }

    public function create()
    {
        $this->validate();

        $branch_location = null;

        if ($this->user_id) {
            $user = User::find($this->user_id);
            $branch_location = $user->branch_location;
        }

        Expense::create([
            'user_id' => !empty($this->user_id) ? $this->user_id : null,
            'item_name' => $this->item_name,
            'branch_location' => $branch_location,
            'description' => $this->description,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date ?? Carbon::today(),
        ]);

        $this->dispatch('flash-message', [
            'message' => 'Data pengeluaran berhasil ditambahkan.',
            'type' => 'create'
        ]);

        $this->reset(['item_name', 'description', 'user_id', 'amount', 'expense_date']);

        $this->getFilteredStats();
        $this->setPage(1);
        $this->dispatch('expenseUpdated');
    }

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'branchId' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function resetFilters()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->branchId = 'all';
    }

    public function exportToExcel()
    {
        return Excel::download(new ExpensesExport($this->startDate, $this->endDate, $this->branchId), 'pengeluaran-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function getBaseQuery()
    {
        $query = Expense::with('user');

        if ($this->startDate) {
            $query->whereDate('expense_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('expense_date', '<=', $this->endDate);
        }

        if ($this->branchId !== 'all') {
            $query->whereHas('user', function ($q) {
                $q->where('id', $this->branchId);
            });
        }

        return $query;
    }

    public function getExpenses()
    {
        return $this->getBaseQuery()
            ->orderBy('expense_date', 'desc')
            ->paginate($this->perPage);
    }

    public function getFilteredStats()
    {
        $query = $this->getBaseQuery();
        
        $this->filteredTotal = $query->sum('amount');
        $this->filteredCount = $query->count();
    }


    public function render()
    {
        $expenses = $this->getExpenses();

        $this->getFilteredStats();

        $branches = \App\Models\User::whereNotNull(
            'branch_location')
            ->select('id', 'branch_location')
            ->distinct()
            ->get();

        return view('livewire.super-admin.expense-table', [
            'expenses' => $expenses,
            'branches' => $branches,
        ]);
    }
}