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
        $transactions = $this->installment->transactions;
        
        return view('livewire.vehicle.installment.history', compact('transactions'))
            ->title('Installment History - ' . ($this->installment->customer->name ?? 'N/A'));
    }
}
