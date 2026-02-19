<?php

namespace App\Livewire\Vehicle\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

    public $search = '';
    public $sortField = 'name';
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
        }
        else {
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
        $customer = Customer::find($id);
        if ($customer) {
            // Delete image if exists
            if ($customer->image) {
                \Storage::disk('public')->delete($customer->image);
            }
            $customer->delete();
            session()->flash('message', __('messages.customer_deleted'));
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
        $customers = Customer::where('type', 'Car-installment')
            ->when($this->search, function ($query) {
            $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('number', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('address', 'like', "%{$this->search}%");
                }
                );
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.vehicle.customer.index', compact('customers'))
            ->title('Car Installment Customers');
    }
}
