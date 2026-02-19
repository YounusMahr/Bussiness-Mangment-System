<?php

namespace App\Livewire\Grocery\Udaar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Udaar;
use App\Models\Customer;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'buy_date';
    public $sortDirection = 'desc';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }
        else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteUdaar($id)
    {
        $udaar = Udaar::find($id);
        if ($udaar) {
            $udaar->delete();
            session()->flash('message', 'Udhaar record deleted successfully.');
            $this->resetPage();
        }
    }

    public function printTable()
    {
        $this->dispatch('print-table');
    }

    public function render()
    {
        $udaars = Udaar::with('product')
            ->when($this->search, function ($query) {
            $query->where(function ($q) {
                    $q->where('customer_name', 'like', "%{$this->search}%")
                        ->orWhere('customer_number', 'like', "%{$this->search}%")
                        ->orWhere('notes', 'like', "%{$this->search}%");
                }
                );
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Load customers to match with udaars
        $customerNames = $udaars->pluck('customer_name')->filter()->unique();
        $customers = Customer::whereIn('name', $customerNames)
            ->where('type', 'Grocery')
            ->get()
            ->keyBy('name');

        return view('livewire.grocery.udaar.index', compact('udaars', 'customers'));
    }
}
