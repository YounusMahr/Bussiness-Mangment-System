<?php

namespace App\Livewire\Grocery\Sales;

use App\Models\Sale;
use App\Models\Customer;
use Livewire\WithPagination;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;
    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;

    public function updatingSearch() { $this->resetPage(); }
    public function sortBy($field) {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteSale(int $saleId): void
    {
        $sale = Sale::find($saleId);
        if ($sale) {
            $sale->delete();
            session()->flash('message', 'Sale deleted successfully.');
            $this->resetPage();
        }
    }

    public function render()
    {
        $sales = Sale::with('saleItems.product', 'user')
            ->when($this->search, function($q) {
                $q->where('customer_name', 'like', "%{$this->search}%")
                  ->orWhere('notes', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Load customers to match with sales
        $customerNames = $sales->pluck('customer_name')->filter()->unique();
        $customers = Customer::whereIn('name', $customerNames)
            ->where('type', 'Grocery')
            ->get()
            ->keyBy('name');

        return view('livewire.grocery.sales.index', compact('sales', 'customers'));
    }
}
