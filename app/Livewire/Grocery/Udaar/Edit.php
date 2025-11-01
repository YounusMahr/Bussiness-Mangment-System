<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Udaar;
use Livewire\Component;

class Edit extends Component
{
    protected $layout = 'layouts.app';

    public $udaarId;
    public $buy_date;
    public $customer_name = '';
    public $customer_number = '';
    public $paid_amount = 0;
    public $total_amount = 0;
    public $remaining_amount = 0;
    public $interest_amount = 0;
    public $due_date;
    public $notes = '';

    protected $rules = [
        'buy_date' => 'required|date',
        'customer_name' => 'required|string|max:255',
        'customer_number' => 'nullable|string|max:255',
        'paid_amount' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'interest_amount' => 'nullable|numeric|min:0',
        'due_date' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    public function mount(Udaar $udaar)
    {
        $this->udaarId = $udaar->id;
        $this->buy_date = $udaar->buy_date->format('Y-m-d');
        $this->customer_name = $udaar->customer_name;
        $this->customer_number = $udaar->customer_number;
        $this->paid_amount = $udaar->paid_amount;
        $this->total_amount = $udaar->paid_amount + $udaar->remaining_amount - $udaar->interest_amount;
        $this->interest_amount = $udaar->interest_amount;
        $this->remaining_amount = $udaar->remaining_amount;
        $this->due_date = $udaar->due_date ? $udaar->due_date->format('Y-m-d') : null;
        $this->notes = $udaar->notes;
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
        $total = $this->total_amount ?? 0;
        $paid = $this->paid_amount ?? 0;
        $interest = $this->interest_amount ?? 0;
        $this->remaining_amount = max(($total + $interest) - $paid, 0);
    }

    public function update()
    {
        $this->validate();
        $this->calculateRemaining();

        $udaar = Udaar::findOrFail($this->udaarId);
        $udaar->update([
            'buy_date' => $this->buy_date,
            'customer_name' => $this->customer_name,
            'customer_number' => $this->customer_number,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'interest_amount' => $this->interest_amount,
            'due_date' => $this->due_date,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Udhaar record updated successfully!');
        return $this->redirectRoute('udaar.index');
    }

    public function render()
    {
        return view('livewire.grocery.udaar.edit')
            ->title('Edit Udhaar');
    }
}
