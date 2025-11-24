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
    public $invest_cash = 0;
    public $interest = 0;
    public $time_period = '';
    public $due_date;
    public $return_amount = 0;
    public $status = 'pending';
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'invest_cash' => 'required|numeric|min:0',
        'interest' => 'nullable|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'return_amount' => 'nullable|numeric|min:0',
        'status' => 'required|in:pending,returned',
        'notes' => 'nullable|string',
    ];

    public function mount(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->customer = $customer;
        $this->date = now()->format('Y-m-d');
    }

    public function updatedInvestCash()
    {
        $this->calculateReturnAmount();
    }

    public function updatedInterest()
    {
        $this->calculateReturnAmount();
    }

    public function calculateReturnAmount()
    {
        $invest = $this->invest_cash ?? 0;
        $interest = $this->interest ?? 0;
        $this->return_amount = $invest + $interest;
    }

    public function save()
    {
        $this->validate();
        $this->calculateReturnAmount();

        GroceryCashTransaction::create([
            'date' => $this->date,
            'customer_id' => $this->customerId,
            'type' => 'cash-in',
            'invest_cash' => $this->invest_cash,
            'interest' => $this->interest,
            'time_period' => $this->time_period,
            'due_date' => $this->due_date,
            'return_amount' => $this->return_amount,
            'status' => $this->status,
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
