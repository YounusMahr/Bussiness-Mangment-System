<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use App\Models\GroceryCashTransaction;
use Livewire\Component;

class CashIn extends Component
{
    protected $layout = 'layouts.app';

    public $customerId;
    public $customer;
    public $date;
    public $amount = 0;
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->customer = $customer;
        $this->date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        GroceryCashTransaction::create([
            'date' => $this->date,
            'customer_id' => $this->customerId,
            'type' => 'cash-in',
            'invest_cash' => $this->amount,
            'interest' => 0,
            'return_amount' => $this->amount,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        session()->flash('message', __('messages.cash_in_created'));
        return $this->redirectRoute('grocery.cash.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.cash.cash-in')
            ->title('Add Cash In');
    }
}
