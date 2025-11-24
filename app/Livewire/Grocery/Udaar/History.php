<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Udaar;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $udaar;

    public function mount(Udaar $udaar)
    {
        $this->udaar = $udaar->load(['transactions.product']);
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        $transactions = $this->udaar->transactions;
        
        return view('livewire.grocery.udaar.udaar-history', compact('transactions'))
            ->title('Udaar History - ' . $this->udaar->customer_name);
    }
}

