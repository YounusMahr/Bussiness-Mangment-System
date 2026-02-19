<?php

namespace App\Livewire\Vehicle\Installment;

use App\Models\Installment;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $installment;

    public function mount(Installment $installment)
    {
        $this->installment = $installment->load(['customer', 'transactions']);
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // Get transactions ordered by date and ID (chronological)
        $transactions = $this->installment->transactions()
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        // Calculate Totals
        // Debit: Increases in amount owed (Car Price + Interest + any returns/refunds)
        // Credit: Decreases in amount owed (Payments made)
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($transactions as $t) {
            if ($t->type === 'add') {
                $totalDebit += (float)($t->new_car_price ?? 0) + (float)($t->new_interest ?? 0);
                $totalCredit += (float)($t->new_paid ?? 0);
            } elseif ($t->type === 'return') {
                // If return_payment exists, it's usually a refund of a payment, which increases debt (Debit)
                $totalDebit += (float)($t->return_payment ?? 0);
            }
        }

        $finalBalance = $totalDebit - $totalCredit;
        
        return view('livewire.vehicle.installment.history', [
            'transactions' => $transactions,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
            'finalBalance' => $finalBalance,
        ])->title(__('messages.installment_history') . ' - ' . ($this->installment->customer->name ?? 'N/A'));
    }
}
