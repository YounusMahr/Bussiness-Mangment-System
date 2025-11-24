<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use App\Models\GroceryCashTransaction;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalCashAdded;
    public $totalReturned;
    public $totalRemaining;
    public $totalCustomers;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Total cash added: sum of return_amount from cash-in transactions
        $this->totalCashAdded = GroceryCashTransaction::where('type', 'cash-in')
            ->sum('return_amount') ?? 0;

        // Total returned: sum of returned_amount from cash-out transactions
        $this->totalReturned = GroceryCashTransaction::where('type', 'cash-out')
            ->sum('returned_amount') ?? 0;

        // Total remaining: total cash added - total returned
        $this->totalRemaining = $this->totalCashAdded - $this->totalReturned;

        // Total customers: count of customers with type 'Grocery' who have transactions
        $this->totalCustomers = Customer::where('type', 'Grocery')
            ->whereHas('groceryCashTransactions')
            ->distinct()
            ->count('id');
    }

    public function render()
    {
        return view('livewire.grocery.cash.dashboard');
    }
}
