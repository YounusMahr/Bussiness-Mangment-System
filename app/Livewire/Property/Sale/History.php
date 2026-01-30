<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotSale;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $sale;

    public function mount(PlotSale $sale)
    {
        $this->sale = $sale->load(['plotPurchase', 'transactions']); 
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // Get transactions ordered by date ascending for running balance calculation
        $transactions = $this->sale->transactions()
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        // Calculate running balance
        // Start from initial sale price (what customer owes us)
        // sale-in = Credit (payment received, reduces what customer owes), sale-out = Debit (refund, increases what customer owes)
        $runningBalance = (float)($this->sale->total_sale_price ?? 0);
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction->type === 'sale-in') {
                // Credit: Subtract the installment amount (reduces what customer owes)
                $amount = (float)($transaction->installment_amount ?? $transaction->paid_amount ?? 0);
                $runningBalance -= $amount;
            } else {
                // Debit: Add the payment amount (increases what customer owes - refund)
                $amount = (float)($transaction->payment_amount ?? $transaction->installment_amount ?? 0);
                $runningBalance += $amount;
            }
            $transaction->running_balance = $runningBalance;
            return $transaction;
        });
        
        // Reverse to show newest records first for display
        $transactionsWithBalance = $transactionsWithBalance->reverse()->values();
        
        // Calculate totals
        // sale-in = Credit, sale-out = Debit
        // Use installment_amount as primary amount for Khata system
        $totalCredit = $transactions->where('type', 'sale-in')->sum(function($t) {
            return (float)($t->installment_amount ?? $t->paid_amount ?? 0);
        });
        $totalDebit = $transactions->where('type', 'sale-out')->sum(function($t) {
            return (float)($t->payment_amount ?? $t->installment_amount ?? 0);
        });
        // Final balance = initial sale price - total credit + total debit
        $initialPrice = (float)($this->sale->total_sale_price ?? 0);
        $finalBalance = $initialPrice - $totalCredit + $totalDebit;
        
        return view('livewire.property.sale.history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Plot Sale History');
    }
}
