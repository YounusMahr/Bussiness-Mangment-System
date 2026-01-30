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
        // Get transactions ordered by date ascending (oldest first), like Digikhata
        $transactions = $this->customer->groceryCashTransactions()
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        // Calculate running balance (remaining amount after each transaction)
        $runningBalance = 0;
        $balanceMap = [];
        foreach ($transactions as $transaction) {
            if ($transaction->type === 'cash-in') {
                $runningBalance += (float)($transaction->return_amount ?? 0);
            } else {
                $runningBalance -= (float)($transaction->returned_amount ?? 0);
            }
            $balanceMap[$transaction->id] = max(0, $runningBalance);
        }
        
        $transactionsWithBalance = $transactions->map(function ($transaction) use ($balanceMap) {
            $transaction->running_balance = $balanceMap[$transaction->id] ?? 0;
            return $transaction;
        });
        
        // Calculate totals
        $totalCredit = (float)($transactions->where('type', 'cash-in')->sum('return_amount') ?? 0);
        $totalDebit = (float)($transactions->where('type', 'cash-out')->sum('returned_amount') ?? 0);
        // Final balance = Total credits - Total debits (remaining amount)
        $finalBalance = max(0, $totalCredit - $totalDebit);
        
        return view('livewire.grocery.cash.history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Cash History - ' . $this->customer->name);
    }
}

