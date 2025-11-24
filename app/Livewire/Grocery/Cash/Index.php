<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use App\Models\GroceryCashTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
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

    public function deleteCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            // Delete all transactions first
            GroceryCashTransaction::where('customer_id', $id)->delete();
            // Delete customer image if exists
            if ($customer->image) {
                \Storage::disk('public')->delete($customer->image);
            }
            $customer->delete();
            session()->flash('message', __('messages.customer_deleted'));
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function render()
    {
        $customers = Customer::where('type', 'Grocery')
            ->with(['groceryCashTransactions' => function($query) {
                $query->orderBy('date', 'desc');
            }])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('number', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Calculate totals for each customer
        foreach ($customers as $customer) {
            $customer->total_cash_in = GroceryCashTransaction::where('customer_id', $customer->id)
                ->where('type', 'cash-in')
                ->sum('return_amount');
            
            $customer->total_cash_out = GroceryCashTransaction::where('customer_id', $customer->id)
                ->where('type', 'cash-out')
                ->sum('returned_amount');
            
            $customer->total_amount = $customer->total_cash_in - $customer->total_cash_out;
            
            // Get latest transaction status
            $latestTransaction = GroceryCashTransaction::where('customer_id', $customer->id)
                ->orderBy('date', 'desc')
                ->first();
            
            $customer->status = $latestTransaction ? $latestTransaction->status : 'pending';
        }

        return view('livewire.grocery.cash.index', compact('customers'));
    }
}
