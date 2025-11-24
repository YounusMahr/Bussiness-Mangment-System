<?php

namespace App\Livewire\Vehicle\Installment;

use App\Models\Installment;
use App\Models\InstallmentTransaction;
use Livewire\Component;

class InstallReturn extends Component
{
    protected $layout = 'layouts.app';

    public $installmentId;
    public $installment;
    public $date;
    public $total_price = 0;
    public $return_payment = 0;
    public $installment_number = '';
    public $remaining = 0;
    public $status = 'pending';
    public $note = '';

    protected $rules = [
        'date' => 'required|date',
        'return_payment' => 'required|numeric|min:0',
        'installment_number' => 'nullable|string|max:255',
        'remaining' => 'nullable|numeric|min:0',
        'status' => 'required|in:pending,paid',
        'note' => 'nullable|string',
    ];

    public function mount(Installment $installment)
    {
        $this->installmentId = $installment->id;
        $this->installment = $installment;
        $this->date = now()->format('Y-m-d');
        $this->total_price = $installment->total_price;
    }

    public function updatedReturnPayment()
    {
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $totalPrice = (float)($this->total_price ?? 0);
        $currentPaid = (float)($this->installment->paid ?? 0);
        $returnPayment = (float)($this->return_payment ?? 0);

        // Remaining = total_price - (current_paid + return_payment)
        $this->remaining = max($totalPrice - ($currentPaid + $returnPayment), 0);

        // Auto-update status
        if ($this->remaining <= 0) {
            $this->status = 'paid';
        } else {
            $this->status = 'pending';
        }
    }

    public function save()
    {
        $this->validate();
        $this->calculateRemaining();

        // Store before values
        $carPriceBefore = $this->installment->car_price;
        $paidBefore = $this->installment->paid;
        $remainingBefore = $this->installment->remaining;
        $interestBefore = $this->installment->interest;
        $totalPriceBefore = $this->installment->total_price;

        // Update installment
        $newPaid = $this->installment->paid + $this->return_payment;
        $newRemaining = $this->remaining;

        $this->installment->update([
            'paid' => $newPaid,
            'remaining' => $newRemaining,
        ]);

        // Create transaction record
        InstallmentTransaction::create([
            'installment_id' => $this->installmentId,
            'date' => $this->date,
            'type' => 'return',
            'return_payment' => $this->return_payment,
            'car_price_before' => $carPriceBefore,
            'paid_before' => $paidBefore,
            'remaining_before' => $remainingBefore,
            'interest_before' => $interestBefore,
            'total_price_before' => $totalPriceBefore,
            'car_price_after' => $this->installment->car_price,
            'paid_after' => $newPaid,
            'remaining_after' => $newRemaining,
            'interest_after' => $this->installment->interest,
            'total_price_after' => $this->installment->total_price,
            'notes' => $this->note,
        ]);

        session()->flash('message', __('messages.return_payment_added'));
        return $this->redirectRoute('vehicle.installment.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.vehicle.installment.install-return')
            ->title('Add Return Payment');
    }
}
