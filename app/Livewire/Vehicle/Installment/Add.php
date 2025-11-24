<?php

namespace App\Livewire\Vehicle\Installment;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\InstallmentTransaction;
use Livewire\Component;

class Add extends Component
{
    protected $layout = 'layouts.app';

    public $customer_id = '';
    public $customer_number = '';
    public $date;
    public $vehicle = '';
    public $model = '';
    public $installment = '';
    public $car_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $interest = 0;
    public $total_price = 0;
    public $note = '';
    public $time_period = '';
    public $due_date;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'date' => 'required|date',
        'vehicle' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'installment' => 'nullable|string',
        'car_price' => 'required|numeric|min:0',
        'paid' => 'required|numeric|min:0',
        'remaining' => 'nullable|numeric|min:0',
        'interest' => 'nullable|numeric|min:0',
        'total_price' => 'nullable|numeric|min:0',
        'note' => 'nullable|string',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
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

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['car_price', 'interest', 'paid'])) {
            $this->calculateAmounts();
        }
    }

    public function calculateAmounts()
    {
        $carPrice = (float)($this->car_price ?? 0);
        $interestAmount = (float)($this->interest ?? 0);
        $paidAmount = (float)($this->paid ?? 0);

        // Total price = car price + interest
        $this->total_price = $carPrice + $interestAmount;

        // Remaining = total price - paid
        $this->remaining = max($this->total_price - $paidAmount, 0);
    }

    public function save()
    {
        $this->validate();
        $this->calculateAmounts();

        $installment = Installment::create([
            'date' => $this->date,
            'customer_id' => $this->customer_id,
            'number' => $this->customer_number,
            'vehicle' => $this->vehicle,
            'model' => $this->model,
            'installment' => $this->installment,
            'car_price' => $this->car_price,
            'paid' => $this->paid,
            'remaining' => $this->remaining,
            'interest' => $this->interest,
            'total_price' => $this->total_price,
            'note' => $this->note,
            'time_period' => $this->time_period,
            'due_date' => $this->due_date,
        ]);

        // Create initial transaction record for the add
        InstallmentTransaction::create([
            'installment_id' => $installment->id,
            'date' => $this->date,
            'type' => 'add',
            'new_car_price' => $this->car_price,
            'new_interest' => $this->interest,
            'new_paid' => $this->paid,
            'new_total_price' => $this->total_price,
            'car_price_before' => 0,
            'paid_before' => 0,
            'remaining_before' => 0,
            'interest_before' => 0,
            'total_price_before' => 0,
            'car_price_after' => $this->car_price,
            'paid_after' => $this->paid,
            'remaining_after' => $this->remaining,
            'interest_after' => $this->interest,
            'total_price_after' => $this->total_price,
            'notes' => $this->note,
        ]);

        session()->flash('message', __('messages.installment_created'));
        return $this->redirectRoute('vehicle.installment.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        $customers = Customer::where('type', 'Car-installment')
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.vehicle.installment.add', compact('customers'))
            ->title('Add Installment');
    }
}
