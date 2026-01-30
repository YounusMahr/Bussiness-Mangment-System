<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Product;
use App\Models\Udaar;
use App\Models\UdaarTransaction;
use Livewire\Component;

class UdaarIn extends Component
{
    protected $layout = 'layouts.app';

    public $udaarId;
    public $udaar;
    public $date;
    public $current_paid = 0;
    public $current_remaining = 0;
    public $credit_balance = 0;
    public $new_udaar_amount = 0;
    public $product_id = '';
    public $interest_amount = 0;
    public $time_period = '';
    public $due_date;
    public $new_remaining = 0;
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'new_udaar_amount' => 'nullable|numeric|min:0',
        'product_id' => 'nullable|exists:products,id',
        'interest_amount' => 'nullable|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    public function mount(Udaar $udaar)
    {
        $this->udaarId = $udaar->id;
        $this->udaar = $udaar;
        $this->date = now()->format('Y-m-d');
        $this->current_paid = $udaar->paid_amount;
        $this->current_remaining = $udaar->remaining_amount;
        // Calculate credit balance (negative remaining means customer has credit)
        $this->credit_balance = $udaar->remaining_amount < 0 ? abs($udaar->remaining_amount) : 0;
        $this->calculateNewRemaining();
    }

    public function updatedNewUdaarAmount()
    {
        // Normalize empty string to 0
        if ($this->new_udaar_amount === '' || $this->new_udaar_amount === null) {
            $this->new_udaar_amount = 0;
        }
        $this->calculateNewRemaining();
    }

    public function updatedInterestAmount()
    {
        // Normalize empty string to 0
        if ($this->interest_amount === '' || $this->interest_amount === null) {
            $this->interest_amount = 0;
        }
        $this->calculateNewRemaining();
    }

    public function calculateNewRemaining()
    {
        // Convert to float, handling empty strings and null values
        $newAmount = (float)($this->new_udaar_amount ?? 0);
        $interest = (float)($this->interest_amount ?? 0);
        $currentRemaining = (float)($this->current_remaining ?? 0);
        
        $totalNewAmount = $newAmount + $interest;
        
        // If customer has credit balance (negative remaining), apply it to new purchase
        // Credit balance reduces the new remaining amount
        $this->new_remaining = $currentRemaining + $totalNewAmount;
    }

    public function save()
    {
        // Normalize empty values to 0 before validation
        if ($this->new_udaar_amount === '' || $this->new_udaar_amount === null) {
            $this->new_udaar_amount = 0;
        }
        if ($this->interest_amount === '' || $this->interest_amount === null) {
            $this->interest_amount = 0;
        }
        
        $this->validate();
        $this->calculateNewRemaining();

        // Update the udaar record - add new udaar amount to remaining
        $udaar = Udaar::findOrFail($this->udaarId);
        
        // Calculate new interest amount (add to existing) - ensure numeric values
        $existingInterest = (float)($udaar->interest_amount ?? 0);
        $newInterest = (float)($this->interest_amount ?? 0);
        $newInterestAmount = $existingInterest + $newInterest;
        
        $updateData = [
            'remaining_amount' => $this->new_remaining,
            'interest_amount' => $newInterestAmount,
            'product_id' => $this->product_id ?: $udaar->product_id, // Update product if selected
            'notes' => $this->notes ? ($udaar->notes ? $udaar->notes . "\n\nNew udaar on " . $this->date . " (Amount: Rs " . number_format($this->new_udaar_amount, 2) . ($this->interest_amount > 0 ? ", Interest: Rs " . number_format($this->interest_amount, 2) : "") . "): " . $this->notes : "New udaar on " . $this->date . " (Amount: Rs " . number_format($this->new_udaar_amount, 2) . ($this->interest_amount > 0 ? ", Interest: Rs " . number_format($this->interest_amount, 2) : "") . "): " . $this->notes) : $udaar->notes,
        ];

        // Update due_date and time_period if provided
        if ($this->due_date) {
            $updateData['due_date'] = $this->due_date;
        }
        if ($this->time_period) {
            $updateData['time_period'] = $this->time_period;
        }

        $udaar->update($updateData);

        // Create transaction record
        UdaarTransaction::create([
            'udaar_id' => $this->udaarId,
            'date' => $this->date,
            'type' => 'udaar-in',
            'new_udaar_amount' => $this->new_udaar_amount,
            'interest_amount' => $this->interest_amount,
            'product_id' => $this->product_id ?: null,
            'time_period' => $this->time_period,
            'due_date' => $this->due_date,
            'paid_amount_before' => $this->current_paid,
            'remaining_amount_before' => $this->current_remaining,
            'paid_amount_after' => $this->current_paid,
            'remaining_amount_after' => $this->new_remaining,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'New udaar amount added successfully!');
        return $this->redirectRoute('udaar.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        $products = \App\Models\Product::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.grocery.udaar.udaar-in', compact('products'))
            ->title('Add New Udaar');
    }
}
