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
        $this->transactions = $this->purchase->transactions;
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        return view('livewire.grocery.purchase.history')
            ->title('Stock Purchase History');
    }
}

