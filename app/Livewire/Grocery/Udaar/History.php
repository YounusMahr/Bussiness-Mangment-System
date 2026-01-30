<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Udaar;
use App\Models\UdaarTransaction;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $udaar;

    public function mount(Udaar $udaar)
    {
        $this->udaar = $udaar;
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // Simple like Cash: date asc, id asc (oldest first). Bypass relation default order.
        $transactions = UdaarTransaction::where('udaar_id', $this->udaar->id)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Running balance: debit adds, credit subtracts. Allow negative (overpayment).
        $runningBalance = 0;
        $balanceMap = [];
        foreach ($transactions as $t) {
            if ($t->type === 'udaar-in') {
                $runningBalance += (float)($t->new_udaar_amount ?? 0);
            } else {
                $runningBalance -= (float)($t->payment_amount ?? 0);
            }
            $balanceMap[$t->id] = $runningBalance;
        }

        $withBalance = $transactions->map(function ($t) use ($balanceMap) {
            $t->running_balance = $balanceMap[$t->id] ?? 0;
            return $t;
        });

        $totalCredit = (float) $transactions->where('type', 'udaar-out')->sum('payment_amount');
        $totalDebit = (float) $transactions->where('type', 'udaar-in')->sum('new_udaar_amount');
        $finalBalance = $totalDebit - $totalCredit;

        return view('livewire.grocery.udaar.udaar-history', [
            'transactions' => $withBalance,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance,
        ])->title(__('messages.udaar_history') . ' - ' . $this->udaar->customer_name);
    }
}
