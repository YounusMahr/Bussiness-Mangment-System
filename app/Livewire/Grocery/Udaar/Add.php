<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Udaar;
use App\Models\UdaarTransaction;
use Livewire\Component;

class Add extends Component
{
    protected $layout = 'layouts.app';

    public $buy_date;
    public $customer_id = '';
    public $customer_name = '';
    public $customer_number = '';
    public $product_id = '';
    public $time_period = '';
    public $paid_amount = 0;
    public $total_amount = 0;
    public $remaining_amount = 0;
    public $interest_amount = 0;
    public $due_date;
    public $notes = '';

    protected $rules = [
        'buy_date' => 'required|date',
        'customer_id' => 'required|exists:customers,id',
        'product_id' => 'nullable|exists:products,id',
        'time_period' => 'nullable|string|max:255',
        'paid_amount' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'interest_amount' => 'nullable|numeric|min:0',
        'due_date' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->buy_date = now()->format('Y-m-d');
    }

    public function updatedCustomerId()
    {
        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if ($customer) {
                $this->customer_name = $customer->name;
                $this->customer_number = $customer->number;
            }
        } else {
            $this->customer_name = '';
            $this->customer_number = '';
        }
    }

    public function updatedTotalAmount()
    {
        $this->calculateRemaining();
    }

    public function updatedPaidAmount()
    {
        $this->calculateRemaining();
    }

    public function updatedInterestAmount()
    {
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $total = (float)($this->total_amount ?? 0);
        $paid = (float)($this->paid_amount ?? 0);
        $interest = (float)($this->interest_amount ?? 0);
        $this->remaining_amount = max(($total + $interest) - $paid, 0);
    }

    public function save()
    {
        $this->validate();
        $this->calculateRemaining();

        $udaar = Udaar::create([
            'buy_date' => $this->buy_date,
            'customer_name' => $this->customer_name,
            'customer_number' => $this->customer_number,
            'product_id' => $this->product_id ?: null,
            'time_period' => $this->time_period,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'interest_amount' => $this->interest_amount,
            'due_date' => $this->due_date,
            'notes' => $this->notes,
        ]);

        // Create initial transaction record for the new udhaar
        UdaarTransaction::create([
            'udaar_id' => $udaar->id,
            'date' => $this->buy_date,
            'type' => 'udaar-in',
            'new_udaar_amount' => $this->remaining_amount + $this->paid_amount - ($this->interest_amount ?? 0),
            'interest_amount' => $this->interest_amount ?? 0,
            'product_id' => $this->product_id ?: null,
            'time_period' => $this->time_period,
            'due_date' => $this->due_date,
            'paid_amount_before' => 0,
            'remaining_amount_before' => 0,
            'paid_amount_after' => $this->paid_amount,
            'remaining_amount_after' => $this->remaining_amount,
            'notes' => $this->notes ?: 'Initial udhaar record created',
        ]);

        session()->flash('message', 'Udhaar record created successfully!');
        return $this->redirectRoute('udaar.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        $customers = Customer::where('type', 'Grocery')
            ->orderBy('name', 'asc')
            ->get();

        $products = Product::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.grocery.udaar.add', compact('customers', 'products'))
            ->title('Add Udhaar');
    }
}
