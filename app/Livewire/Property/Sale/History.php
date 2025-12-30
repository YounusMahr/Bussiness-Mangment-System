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
        $this->sale = $sale; 
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        // TODO: Load transactions from PlotSaleTransaction model when implemented
        $transactions = [];
        
        return view('livewire.property.sale.history', [
            'transactions' => $transactions
        ])->title('Plot Sale History');
    }
}
