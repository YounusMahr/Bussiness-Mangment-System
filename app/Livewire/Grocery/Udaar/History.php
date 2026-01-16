<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Udaar;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $udaar;

    public function mount(Udaar $udaar)
    {
        $this->udaar = $udaar->load(['transactions.product']);
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // Get transactions ordered by date ascending for running balance calculation
        $transactions = $this->udaar->transactions()
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        // Calculate running balance
        $runningBalance = 0;
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction->type === 'udaar-in') {
                // Credit: Add the new udaar amount
                $runningBalance += (float)($transaction->new_udaar_amount ?? 0);
            } else {
                // Debit: Subtract the payment amount
                $runningBalance -= (float)($transaction->payment_amount ?? 0);
            }
            $transaction->running_balance = $runningBalance;
            return $transaction;
        });
        
        // Calculate totals
        $totalCredit = $transactions->where('type', 'udaar-in')->sum('new_udaar_amount');
        $totalDebit = $transactions->where('type', 'udaar-out')->sum('payment_amount');
        $finalBalance = $totalCredit - $totalDebit;
        
        return view('livewire.grocery.udaar.udaar-history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Udaar History - ' . $this->udaar->customer_name);
    }
}

