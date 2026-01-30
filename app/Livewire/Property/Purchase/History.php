<?php

namespace App\Livewire\Property\Purchase;

use App\Models\PlotPurchase;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $purchase;

    public function mount(PlotPurchase $purchase)
    {
        $this->purchase = $purchase->load(['customer', 'transactions']);
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // Get transactions ordered by date ascending for running balance calculation
        $transactions = $this->purchase->transactions()
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        // Calculate running balance
        // Start from initial plot price (what we owe)
        // purchase-in = Credit (payment received, reduces debt), purchase-out = Debit (payment made, increases debt)
        $runningBalance = (float)($this->purchase->plot_price ?? 0);
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction->type === 'purchase-in') {
                // Credit: Subtract the installment amount (reduces what we owe)
                $amount = (float)($transaction->installment_amount ?? $transaction->paid_amount ?? 0);
                $runningBalance -= $amount;
            } else {
                // Debit: Add the payment amount (increases what we owe)
                $amount = (float)($transaction->payment_amount ?? $transaction->installment_amount ?? 0);
                $runningBalance += $amount;
            }
            $transaction->running_balance = $runningBalance;
            return $transaction;
        });
        
        // Reverse to show newest records first for display
        $transactionsWithBalance = $transactionsWithBalance->reverse()->values();
        
        // Calculate totals
        // purchase-in = Credit, purchase-out = Debit
        // Use installment_amount as primary amount for Khata system
        $totalCredit = $transactions->where('type', 'purchase-in')->sum(function($t) {
            return (float)($t->installment_amount ?? $t->paid_amount ?? 0);
        });
        $totalDebit = $transactions->where('type', 'purchase-out')->sum(function($t) {
            return (float)($t->payment_amount ?? $t->installment_amount ?? 0);
        });
        // Final balance = initial plot price - total credit + total debit
        $initialPrice = (float)($this->purchase->plot_price ?? 0);
        $finalBalance = $initialPrice - $totalCredit + $totalDebit;
        
        return view('livewire.property.purchase.history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Plot Purchase History');
    }
}
