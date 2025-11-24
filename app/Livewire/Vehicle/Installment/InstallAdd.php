<?php

namespace App\Livewire\Vehicle\Installment;

use App\Models\Installment;
use App\Models\InstallmentTransaction;
use Livewire\Component;

class InstallAdd extends Component
{
    protected $layout = 'layouts.app';

    public $installmentId;
    public $installment;
    public $date;
    public $current_car_price = 0;
    public $current_interest = 0;
    public $current_paid = 0;
    public $current_remaining = 0;
    public $current_total_price = 0;
    public $new_car_price = 0;
    public $new_interest = 0;
    public $new_paid = 0;
    public $new_total_price = 0;
    public $new_remaining = 0;
    public $time_period = '';
    public $due_date;
    public $note = '';

    protected $rules = [
        'date' => 'required|date',
        'new_car_price' => 'required|numeric|min:0',
        'new_interest' => 'nullable|numeric|min:0',
        'new_paid' => 'required|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'note' => 'nullable|string',
    ];

    public function mount(Installment $installment)
    {
        $this->installmentId = $installment->id;
        $this->installment = $installment;
        $this->date = now()->format('Y-m-d');
        $this->current_car_price = $installment->car_price;
        $this->current_interest = $installment->interest;
        $this->current_paid = $installment->paid;
        $this->current_remaining = $installment->remaining;
        $this->current_total_price = $installment->total_price;
        $this->calculateNewAmounts();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['new_car_price', 'new_interest', 'new_paid'])) {
            $this->calculateNewAmounts();
        }
    }

    public function calculateNewAmounts()
    {
        $newCarPrice = (float)($this->new_car_price ?? 0);
        $newInterest = (float)($this->new_interest ?? 0);
        $newPaid = (float)($this->new_paid ?? 0);

        // New total price = new car price + new interest
        $this->new_total_price = $newCarPrice + $newInterest;

        // New remaining = new total price - new paid
        $newRemaining = max($this->new_total_price - $newPaid, 0);

        // Total remaining = current remaining + new remaining
        $this->new_remaining = $this->current_remaining + $newRemaining;
    }

    public function save()
    {
        $this->validate();
        $this->calculateNewAmounts();

        // Store before values
        $carPriceBefore = $this->installment->car_price;
        $interestBefore = $this->installment->interest;
        $paidBefore = $this->installment->paid;
        $remainingBefore = $this->installment->remaining;
        $totalPriceBefore = $this->installment->total_price;

        // Calculate new totals (add to existing)
        $newCarPrice = $this->installment->car_price + $this->new_car_price;
        $newInterest = $this->installment->interest + $this->new_interest;
        $newPaid = $this->installment->paid + $this->new_paid;
        $newTotalPrice = $newCarPrice + $newInterest;
        $newRemaining = max($newTotalPrice - $newPaid, 0);

        // Update installment
        $this->installment->update([
            'car_price' => $newCarPrice,
            'interest' => $newInterest,
            'paid' => $newPaid,
            'total_price' => $newTotalPrice,
            'remaining' => $newRemaining,
            'time_period' => $this->time_period ?: $this->installment->time_period,
            'due_date' => $this->due_date ?: $this->installment->due_date,
        ]);

        // Create transaction record
        InstallmentTransaction::create([
            'installment_id' => $this->installmentId,
            'date' => $this->date,
            'type' => 'add',
            'new_car_price' => $this->new_car_price,
            'new_interest' => $this->new_interest,
            'new_paid' => $this->new_paid,
            'new_total_price' => $this->new_total_price,
            'car_price_before' => $carPriceBefore,
            'paid_before' => $paidBefore,
            'remaining_before' => $remainingBefore,
            'interest_before' => $interestBefore,
            'total_price_before' => $totalPriceBefore,
            'car_price_after' => $newCarPrice,
            'paid_after' => $newPaid,
            'remaining_after' => $newRemaining,
            'interest_after' => $newInterest,
            'total_price_after' => $newTotalPrice,
            'notes' => $this->note,
        ]);

        session()->flash('message', __('messages.new_installment_added'));
        return $this->redirectRoute('vehicle.installment.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.vehicle.installment.install-add')
            ->title('Add New Installment Amount');
    }
}
