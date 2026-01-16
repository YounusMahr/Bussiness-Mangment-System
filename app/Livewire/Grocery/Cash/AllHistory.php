<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\GroceryCashTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class AllHistory extends Component
{
    use WithPagination;
    
    protected $layout = 'layouts.app';
    
    public $type; // 'credit' or 'debit'
    public $search = '';

    public function mount($type = 'credit')
    {
        $this->type = $type;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get all transactions (both credit and debit) ordered by date
        $transactions = GroceryCashTransaction::with('customer')
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('number', 'like', "%{$this->search}%");
                })->orWhere('notes', 'like', "%{$this->search}%");
            })
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        // Calculate running balance
        $runningBalance = 0;
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction->type === 'cash-in') {
                $runningBalance += (float)($transaction->return_amount ?? 0);
            } else {
                $runningBalance -= (float)($transaction->returned_amount ?? 0);
            }
            $transaction->running_balance = $runningBalance;
            return $transaction;
        });
        
        // Paginate the results
        $perPage = 20;
        $currentPage = request()->get('page', 1);
        $items = $transactionsWithBalance->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $transactionsWithBalance->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Calculate totals
        $totalCredit = $transactions->where('type', 'cash-in')->sum('return_amount');
        $totalDebit = $transactions->where('type', 'cash-out')->sum('returned_amount');
        $finalBalance = $totalCredit - $totalDebit;
        
        return view('livewire.grocery.cash.all-history', [
            'transactions' => $paginator,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Cash History - All Transactions');
    }
}

