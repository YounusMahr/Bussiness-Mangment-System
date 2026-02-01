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
        // Credit only - payment received (sale-in)
        $transactions = $this->sale->transactions()
            ->where('type', 'sale-in')
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Running balance: remaining to receive after each credit (payment)
        $salePrice = (float)($this->sale->total_sale_price ?? 0);
        $runningBalance = $salePrice;
        $transactionsWithBalance = $transactions->map(function ($transaction) use (&$runningBalance) {
            $amount = (float)($transaction->payment_amount ?? $transaction->installment_amount ?? $transaction->paid_amount ?? 0);
            $runningBalance -= $amount;
            $transaction->running_balance = max($runningBalance, 0);
            return $transaction;
        });

        $totalCredit = (float) $transactions->sum(function ($t) {
            return (float)($t->payment_amount ?? $t->installment_amount ?? $t->paid_amount ?? 0);
        });
        $remainingToReceive = max($salePrice - $totalCredit, 0);

        return view('livewire.property.sale.history', [
            'transactions' => $transactionsWithBalance,
            'totalCredit' => $totalCredit,
            'salePrice' => $salePrice,
            'remainingToReceive' => $remainingToReceive,
        ])->title(__('messages.plot_sale_history') ?? 'Plot Sale History');
    }
}
