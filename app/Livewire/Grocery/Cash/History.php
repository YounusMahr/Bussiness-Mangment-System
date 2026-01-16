<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('groceryCashTransactions');
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // Get transactions ordered by date ascending for running balance calculation
        $transactions = $this->customer->groceryCashTransactions()
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
        
        // Calculate totals
        $totalCredit = $transactions->where('type', 'cash-in')->sum('return_amount');
        $totalDebit = $transactions->where('type', 'cash-out')->sum('returned_amount');
        $finalBalance = $totalCredit - $totalDebit;
        
        return view('livewire.grocery.cash.history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Cash History - ' . $this->customer->name);
    }
}

