<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use App\Models\GroceryCashTransaction;
use Livewire\Component;

class CashOut extends Component
{
    protected $layout = 'layouts.app';

    public $customerId;
    public $customer;
    public $date;
    public $available_balance = 0;
    public $returned_amount = 0;
    public $remaining_balance = 0;
    public $status = 'pending';
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'available_balance' => 'required|numeric|min:0',
        'returned_amount' => 'required|numeric|min:0',
        'remaining_balance' => 'nullable|numeric|min:0',
        'status' => 'required|in:pending,returned',
        'notes' => 'nullable|string',
    ];

    public function mount(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->customer = $customer;
        $this->date = now()->format('Y-m-d');
        
        // Calculate available balance from cash-in transactions
        $totalCashIn = GroceryCashTransaction::where('customer_id', $customer->id)
            ->where('type', 'cash-in')
            ->sum('return_amount');
        
        $totalCashOut = GroceryCashTransaction::where('customer_id', $customer->id)
            ->where('type', 'cash-out')
            ->sum('returned_amount');
        
        $this->available_balance = $totalCashIn - $totalCashOut;
        $this->calculateRemainingBalance();
    }

    public function updatedReturnedAmount()
    {
        $this->calculateRemainingBalance();
    }

    public function calculateRemainingBalance()
    {
        $available = $this->available_balance ?? 0;
        $returned = $this->returned_amount ?? 0;
        $this->remaining_balance = max($available - $returned, 0);
    }

    public function save()
    {
        $this->validate();
        $this->calculateRemainingBalance();

        GroceryCashTransaction::create([
            'date' => $this->date,
            'customer_id' => $this->customerId,
            'type' => 'cash-out',
            'available_balance' => $this->available_balance,
            'returned_amount' => $this->returned_amount,
            'remaining_balance' => $this->remaining_balance,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        session()->flash('message', __('messages.cash_out_created'));
        return $this->redirectRoute('grocery.cash.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.cash.cash-out')
            ->title('Add Cash Out');
    }
}
