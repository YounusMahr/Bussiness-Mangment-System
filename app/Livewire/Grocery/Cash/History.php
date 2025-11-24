<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use Livewire\Component;

class History extends Component
{
    protected $layout = 'layouts.app';

    public $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('groceryCashTransactions');
    }

    public function printHistory()
    {
        $this->dispatch('print-history');
    }

    public function render()
    {
        $transactions = $this->customer->groceryCashTransactions()->orderBy('date', 'desc')->get();
        
        return view('livewire.grocery.cash.history', compact('transactions'))
            ->title('Cash History - ' . $this->customer->name);
    }
}

