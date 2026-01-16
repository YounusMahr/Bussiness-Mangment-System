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
        $this->purchase = $purchase->load('customer');
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // TODO: Load transactions from PlotPurchaseTransaction model when implemented
        $transactions = [];
        
        return view('livewire.property.purchase.history', [
            'transactions' => $transactions
        ])->title('Plot Purchase History');
    }
}
