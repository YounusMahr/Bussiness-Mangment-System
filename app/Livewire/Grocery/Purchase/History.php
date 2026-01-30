<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $purchase;
    public $transactions;

    public function mount(StockPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->purchase = $purchase->load('transactions');
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
        
        // Calculate running balance for financial amounts
        // stock-in = Debit (increases debt/remaining), stock-out = Credit (reduces debt/remaining)
        $runningBalance = 0;
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction->type === 'stock-in') {
                // Debit: Add the new purchase amount (increases what we owe)
                $newAmount = (float)($transaction->new_goods_total_price ?? 0);
                $runningBalance += $newAmount;
            } else {
                // Credit: Subtract the return payment (reduces what we owe)
                $returnPayment = (float)($transaction->return_payment ?? 0);
                $runningBalance -= $returnPayment;
            }
            $transaction->running_balance = $runningBalance;
            return $transaction;
        });
        
        // Reverse to show newest records first for display
        $transactionsWithBalance = $transactionsWithBalance->reverse()->values();
        
        // Calculate totals
        // stock-in = Debit, stock-out = Credit
        $totalDebit = $transactions->where('type', 'stock-in')->sum('new_goods_total_price');
        $totalCredit = $transactions->where('type', 'stock-out')->sum('return_payment');
        $finalBalance = $totalDebit - $totalCredit;
        
        return view('livewire.grocery.purchase.history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance
        ])->title('Stock Purchase History');
    }
}

