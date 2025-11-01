<?php

namespace App\Livewire\CarRent\Udaar;

use Livewire\Component;
use App\Models\CarRentUdaar;
use App\Models\VehicleBooking;

class Add extends Component
{
    public $date;
    public $booking_id = null;
    public $customer = '';
    public $total_amount = 0;
    public $paid_amount = 0;
    public $udaar_amount = 0;
    public $status = 'pending';
    public $due_date = null;

    protected function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'booking_id' => ['nullable', 'integer', 'exists:vehicle_bookings,id'],
            'customer' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'udaar_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid,unpaid'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updatedBookingId(): void
    {
        if ($this->booking_id) {
            $booking = VehicleBooking::find($this->booking_id);
            if ($booking) {
                $this->customer = $booking->customer_name;
                $this->total_amount = $booking->total_price;
                $this->calculateUdaar();
            }
        } else {
            $this->customer = '';
            $this->total_amount = 0;
            $this->calculateUdaar();
        }
    }

    public function updatedTotalAmount(): void
    {
        $this->calculateUdaar();
    }

    public function updatedPaidAmount(): void
    {
        $this->calculateUdaar();
    }

    public function calculateUdaar(): void
    {
        $this->udaar_amount = max(($this->total_amount ?? 0) - ($this->paid_amount ?? 0), 0);
        
        // Auto-update status based on payment
        if ($this->udaar_amount == 0 && $this->total_amount > 0) {
            $this->status = 'paid';
        } elseif ($this->udaar_amount > 0 && $this->paid_amount == 0) {
            $this->status = 'unpaid';
        } elseif ($this->udaar_amount > 0 && $this->paid_amount > 0) {
            $this->status = 'pending';
        }
    }

    public function save(): void
    {
        $this->validate();

        CarRentUdaar::create([
            'date' => $this->date,
            'booking_id' => $this->booking_id ?: null,
            'customer' => $this->customer,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'udaar_amount' => $this->udaar_amount,
            'status' => $this->status,
            'due_date' => $this->due_date ?: null,
        ]);

        session()->flash('message', 'Udhaar record created successfully!');
        $this->redirectRoute('car-rent.udaar.index', navigate: true);
    }

    public function render()
    {
        $bookings = VehicleBooking::with('vehicle')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('livewire.car-rent.udaar.add', compact('bookings'));
    }
}
