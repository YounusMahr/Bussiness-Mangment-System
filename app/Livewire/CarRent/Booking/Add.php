<?php

namespace App\Livewire\CarRent\Booking;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\VehicleBooking;

class Add extends Component
{
    public $date;
    public $vehicle_id = null;
    public $price = 0;
    public $customer_name = '';
    public $customer_number = '';
    public $rent_days = 1;
    public $total_price = 0;
    public $return_date;
    public $notes = '';

    protected function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_number' => ['nullable', 'string', 'max:255'],
            'rent_days' => ['required', 'integer', 'min:1'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'return_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
        $this->calculateReturnDate();
    }

    public function updatedVehicleId(): void
    {
        if ($this->vehicle_id) {
            $vehicle = Vehicle::find($this->vehicle_id);
            if ($vehicle && $vehicle->rent_price) {
                $this->price = $vehicle->rent_price;
                $this->calculateTotal();
            }
        } else {
            $this->price = 0;
            $this->calculateTotal();
        }
    }

    public function updatedPrice(): void
    {
        $this->calculateTotal();
    }

    public function updatedRentDays(): void
    {
        $this->calculateTotal();
        $this->calculateReturnDate();
    }

    public function updatedDate(): void
    {
        $this->calculateReturnDate();
    }

    public function calculateTotal(): void
    {
        $this->total_price = ($this->price ?? 0) * ($this->rent_days ?? 1);
    }

    public function calculateReturnDate(): void
    {
        if ($this->date && $this->rent_days) {
            $this->return_date = date('Y-m-d', strtotime($this->date . ' + ' . $this->rent_days . ' days'));
        }
    }

    public function save(): void
    {
        $this->validate();

        VehicleBooking::create([
            'date' => $this->date,
            'vehicle_id' => $this->vehicle_id,
            'price' => $this->price,
            'customer_name' => $this->customer_name,
            'customer_number' => $this->customer_number ?: null,
            'rent_days' => $this->rent_days,
            'total_price' => $this->total_price,
            'return_date' => $this->return_date,
            'notes' => $this->notes ?: null,
        ]);

        // Update vehicle status to 'rented'
        $vehicle = Vehicle::find($this->vehicle_id);
        if ($vehicle) {
            $vehicle->update(['status' => 'rented']);
        }

        session()->flash('message', 'Booking created successfully!');
        $this->redirectRoute('bookings.index', navigate: true);
    }

    public function render()
    {
        $vehicles = Vehicle::where('is_active', true)
            ->where('status', 'available')
            ->get();
        
        return view('livewire.car-rent.booking.add', compact('vehicles'));
    }
}
