<?php

namespace App\Livewire\CarRent\Udaar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CarRentUdaar;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $viewingUdaarId = null;
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

    public function viewUdaar($id)
    {
        $this->viewingUdaarId = $id;
    }

    public function closeView()
    {
        $this->viewingUdaarId = null;
    }

    public function getViewingUdaarProperty()
    {
        return $this->viewingUdaarId ? CarRentUdaar::with('booking.vehicle')->find($this->viewingUdaarId) : null;
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
        $udaar = CarRentUdaar::find($id);
        if ($udaar) {
            $udaar->delete();
            session()->flash('message', 'Udhaar record deleted successfully.');
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function render()
    {
        $udaars = CarRentUdaar::with('booking.vehicle')
            ->when($this->search, function ($query) {
                $query->where('customer', 'like', "%{$this->search}%")
                      ->orWhereHas('booking', function($q) {
                          $q->where('customer_name', 'like', "%{$this->search}%")
                            ->orWhere('customer_number', 'like', "%{$this->search}%");
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.car-rent.udaar.index', compact('udaars'));
    }
}
