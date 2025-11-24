<?php

namespace App\Livewire\Vehicle\Installment;

use App\Models\Customer;
use App\Models\Installment;
use Livewire\Component;

class Edit extends Component
{
    protected $layout = 'layouts.app';

    public $installmentId;
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

    public function mount(Installment $installment)
    {
        $this->installmentId = $installment->id;
        $this->customer_id = $installment->customer_id;
        $this->customer_number = $installment->number ?? '';
        $this->date = $installment->date->format('Y-m-d');
        $this->vehicle = $installment->vehicle ?? '';
        $this->model = $installment->model ?? '';
        $this->installment = $installment->installment ?? '';
        $this->car_price = $installment->car_price ?? 0;
        $this->paid = $installment->paid ?? 0;
        $this->remaining = $installment->remaining ?? 0;
        $this->interest = $installment->interest ?? 0;
        $this->total_price = $installment->total_price ?? 0;
        $this->note = $installment->note ?? '';
        $this->time_period = $installment->time_period ?? '';
        $this->due_date = $installment->due_date ? $installment->due_date->format('Y-m-d') : null;
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

    public function update()
    {
        $this->validate();
        $this->calculateAmounts();

        $installment = Installment::findOrFail($this->installmentId);
        $installment->update([
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

        session()->flash('message', __('messages.installment_updated'));
        return $this->redirectRoute('vehicle.installment.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        $customers = Customer::where('type', 'Car-installment')
            ->orderBy('name', 'asc')
            ->get();

        return view('livewire.vehicle.installment.edit', compact('customers'))
            ->title('Edit Installment');
    }
}
