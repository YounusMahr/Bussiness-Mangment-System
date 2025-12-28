<?php

namespace App\Livewire\Grocery\Udaar;

use App\Models\Udaar;
use App\Models\UdaarTransaction;
use Livewire\Component;

class UdaarOut extends Component
{
    protected $layout = 'layouts.app';

    public $udaarId;
    public $udaar;
    public $date;
    public $current_paid = 0;
    public $current_remaining = 0;
    public $return_amount = 0;
    public $new_paid = 0;
    public $new_remaining = 0;
    public $credit_balance = 0;
    public $notes = '';

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'return_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function mount(Udaar $udaar)
    {
        $this->udaarId = $udaar->id;
        $this->udaar = $udaar;
        $this->date = now()->format('Y-m-d');
        $this->current_paid = $udaar->paid_amount;
        $this->current_remaining = $udaar->remaining_amount;
        $this->calculateNewAmounts();
    }

    public function updatedReturnAmount()
    {
        $this->calculateNewAmounts();
    }

    public function calculateNewAmounts()
    {
        $payment = $this->return_amount ?? 0;
        // Allow overpayment - customer can pay more than remaining amount
        // This creates a credit balance for future purchases
        $this->new_paid = $this->current_paid + $payment;
        $this->new_remaining = $this->current_remaining - $payment;
        
        // Calculate credit balance (negative remaining means customer has credit)
        if ($this->new_remaining < 0) {
            $this->credit_balance = abs($this->new_remaining);
        } else {
            $this->credit_balance = 0;
        }
    }

    public function save()
    {
        $this->validate();
        $this->calculateNewAmounts();

        // Update the udaar record - payment reduces remaining amount
        $udaar = Udaar::findOrFail($this->udaarId);
        $udaar->update([
            'paid_amount' => $this->new_paid,
            'remaining_amount' => $this->new_remaining,
            'notes' => $this->notes ? ($udaar->notes ? $udaar->notes . "\n\nPayment on " . $this->date . " (Rs " . number_format($this->return_amount, 2) . "): " . $this->notes : "Payment on " . $this->date . " (Rs " . number_format($this->return_amount, 2) . "): " . $this->notes) : $udaar->notes,
        ]);

        // Create transaction record
        UdaarTransaction::create([
            'udaar_id' => $this->udaarId,
            'date' => $this->date,
            'type' => 'udaar-out',
            'payment_amount' => $this->return_amount,
            'paid_amount_before' => $this->current_paid,
            'remaining_amount_before' => $this->current_remaining,
            'paid_amount_after' => $this->new_paid,
            'remaining_amount_after' => $this->new_remaining,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Payment processed successfully!');
        return $this->redirectRoute('udaar.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.udaar.udaar-out')
            ->title('Pay Udaar Amount');
    }
}
