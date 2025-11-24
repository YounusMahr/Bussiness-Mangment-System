<?php

namespace App\Livewire\Grocery\Sales;

use App\Models\Sale;
use Livewire\Component;

class Details extends Component
{
    protected $layout = 'layouts.app';

    public $sale;

    public function mount(Sale $sale)
    {
        $this->sale = $sale->load(['saleItems.product', 'saleItems.category', 'user']);
    }

    public function printDetails()
    {
        $this->dispatch('print-details');
    }

    public function render()
    {
        return view('livewire.grocery.sales.details')
            ->title('Sale Details - ' . ($this->sale->customer_name ?: 'Walk-in Customer'));
    }
}
