<?php

namespace App\Livewire\CarRent\Booking;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VehicleBooking;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function delete($id)
    {
        $booking = VehicleBooking::find($id);
        if ($booking) {
            // Update vehicle status back to available
            $vehicle = $booking->vehicle;
            if ($vehicle) {
                $vehicle->update(['status' => 'available']);
            }
            $booking->delete();
            session()->flash('message', 'Booking deleted successfully.');
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function getRemainingDays($returnDate)
    {
        $return = \Carbon\Carbon::parse($returnDate);
        $today = \Carbon\Carbon::today();
        $daysRemaining = $today->diffInDays($return, false);
        
        return $daysRemaining;
    }

    public function render()
    {
        $bookings = VehicleBooking::with('vehicle')
            ->when($this->search, function ($query) {
                $query->where('customer_name', 'like', "%{$this->search}%")
                      ->orWhere('customer_number', 'like', "%{$this->search}%")
                      ->orWhere('notes', 'like', "%{$this->search}%")
                      ->orWhereHas('vehicle', function($q) {
                          $q->where('Vehicle_name', 'like', "%{$this->search}%");
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.car-rent.booking.index', compact('bookings'));
    }
}
