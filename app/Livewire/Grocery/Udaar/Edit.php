<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Product;
use App\Models\Udaar;
use App\Models\UdaarTransaction;
use Livewire\Component;

class Edit extends Component
{
    protected $layout = 'layouts.app';

    public $udaarId;
    public $buy_date;
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
        'customer_name' => 'required|string|max:255',
        'customer_number' => 'nullable|string|max:255',
        'product_id' => 'nullable|exists:products,id',
        'time_period' => 'nullable|string|max:255',
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
        $this->product_id = $udaar->product_id;
        $this->time_period = $udaar->time_period ?? '';
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
        $total = (float)($this->total_amount ?? 0);
        $paid = (float)($this->paid_amount ?? 0);
        $interest = (float)($this->interest_amount ?? 0);
        $this->remaining_amount = max(($total + $interest) - $paid, 0);
    }

    public function update()
    {
        $this->validate();
        $this->calculateRemaining();

        $udaar = Udaar::findOrFail($this->udaarId);
        
        // Store old values before update
        $oldPaidAmount = $udaar->paid_amount;
        $oldRemainingAmount = $udaar->remaining_amount;
        $oldInterestAmount = $udaar->interest_amount ?? 0;
        $oldTotalAmount = $udaar->paid_amount + $udaar->remaining_amount - $oldInterestAmount;
        $newTotalAmount = $this->total_amount;
        
        // Calculate the difference
        $paidDifference = $this->paid_amount - $oldPaidAmount;
        $remainingDifference = $this->remaining_amount - $oldRemainingAmount;
        $totalDifference = $newTotalAmount - $oldTotalAmount;
        $interestDifference = ($this->interest_amount ?? 0) - $oldInterestAmount;
        
        $udaar->update([
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

        // Create transaction record for the edit if there are significant changes
        if (abs($totalDifference) > 0.01 || abs($paidDifference) > 0.01 || abs($remainingDifference) > 0.01) {
            // Determine transaction type based on changes
            // If remaining amount increased, it's a debit (udaar-in)
            // If remaining amount decreased, it's a credit (udaar-out)
            if ($remainingDifference > 0) {
                // Debit: New purchase/amount added
                UdaarTransaction::create([
                    'udaar_id' => $udaar->id,
                    'date' => now()->format('Y-m-d'),
                    'type' => 'udaar-in',
                    'new_udaar_amount' => abs($totalDifference),
                    'interest_amount' => abs($interestDifference),
                    'product_id' => $this->product_id ?: null,
                    'time_period' => $this->time_period,
                    'due_date' => $this->due_date,
                    'paid_amount_before' => $oldPaidAmount,
                    'remaining_amount_before' => $oldRemainingAmount,
                    'paid_amount_after' => $this->paid_amount,
                    'remaining_amount_after' => $this->remaining_amount,
                    'notes' => $this->notes ?: 'Udhaar record updated',
                ]);
            } elseif ($remainingDifference < 0) {
                // Credit: Payment made
                UdaarTransaction::create([
                    'udaar_id' => $udaar->id,
                    'date' => now()->format('Y-m-d'),
                    'type' => 'udaar-out',
                    'payment_amount' => abs($remainingDifference),
                    'paid_amount_before' => $oldPaidAmount,
                    'remaining_amount_before' => $oldRemainingAmount,
                    'paid_amount_after' => $this->paid_amount,
                    'remaining_amount_after' => $this->remaining_amount,
                    'notes' => $this->notes ?: 'Udhaar record updated',
                ]);
            } else {
                // Only paid amount changed, create a credit transaction
                if ($paidDifference > 0) {
                    UdaarTransaction::create([
                        'udaar_id' => $udaar->id,
                        'date' => now()->format('Y-m-d'),
                        'type' => 'udaar-out',
                        'payment_amount' => abs($paidDifference),
                        'paid_amount_before' => $oldPaidAmount,
                        'remaining_amount_before' => $oldRemainingAmount,
                        'paid_amount_after' => $this->paid_amount,
                        'remaining_amount_after' => $this->remaining_amount,
                        'notes' => $this->notes ?: 'Udhaar record updated - payment adjustment',
                    ]);
                }
            }
        }

        session()->flash('message', 'Udhaar record updated successfully!');
        return $this->redirectRoute('udaar.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.grocery.udaar.edit', compact('products'))
            ->title('Edit Udhaar');
    }
}
