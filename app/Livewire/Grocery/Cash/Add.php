<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use App\Models\GroceryCashTransaction;
use Livewire\Component;

class Add extends Component
{
    protected $layout = 'layouts.app';

    public $customer_id = '';
    public $customer_number = '';
    public $date;
    public $amount = 0;
    public $notes = '';

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updatedCustomerId()
    {
        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if ($customer) {
                $this->customer_number = $customer->number;
            }
        } else {
            $this->customer_number = '';
        }
    }

    public function save()
    {
        $this->validate();

        GroceryCashTransaction::create([
            'date' => $this->date,
            'customer_id' => $this->customer_id,
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
        $customers = Customer::where('type', 'Grocery')
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.grocery.cash.add', compact('customers'))
            ->title('Add Cash In');
    }
}
