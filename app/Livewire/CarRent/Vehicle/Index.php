<?php

namespace App\Livewire\CarRent\Vehicle;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vehicle;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'Vehicle_name';
    public $sortDirection = 'asc';
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
        $vehicle = Vehicle::find($id);
        if ($vehicle) {
            $vehicle->delete();
            session()->flash('message', 'Vehicle deleted successfully.');
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function printTable()
    {
        $this->dispatch('print-table');
    }

    public function render()
    {
        $vehicles = Vehicle::when($this->search, function ($query) {
                $query->where('Vehicle_name', 'like', "%{$this->search}%")
                      ->orWhere('model', 'like', "%{$this->search}%")
                      ->orWhere('status', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.car-rent.vehicle.index', compact('vehicles'));
    }
}
