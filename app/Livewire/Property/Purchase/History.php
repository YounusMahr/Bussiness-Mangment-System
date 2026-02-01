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
        // Only debit (purchase-out) transactions - user pays for plot
        $transactions = $this->purchase->transactions()
            ->where('type', 'purchase-out')
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Running balance: remaining to pay after each debit (payment)
        // Start with total plot cost; each payment reduces remaining
        $plotCost = (float)($this->purchase->plot_price ?? 0);
        $runningBalance = $plotCost;
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            $amount = (float)($transaction->payment_amount ?? $transaction->installment_amount ?? 0);
            $runningBalance -= $amount;
            $transaction->running_balance = max($runningBalance, 0);
            return $transaction;
        });

        $totalDebit = (float) $transactions->sum(function ($t) {
            return (float)($t->payment_amount ?? $t->installment_amount ?? 0);
        });
        $remainingToPay = max($plotCost - $totalDebit, 0);

        return view('livewire.property.purchase.history', [
            'transactions' => $transactionsWithBalance,
            'totalDebit' => $totalDebit,
            'plotCost' => $plotCost,
            'remainingToPay' => $remainingToPay,
        ])->title('Plot Purchase History - Payments');
    }
}
