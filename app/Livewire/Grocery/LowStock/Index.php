<?php

namespace App\Livewire\Grocery\LowStock;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
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

    public function printTable()
    {
        $this->dispatch('print-table');
    }

    public function render()
    {
        // Get products with quantity <= 10 (low stock) or quantity == 0 (empty/sold out)
        $products = Product::where(function ($query) {
            $query->where('quantity', '<=', 10);
        })
            ->when($this->search, function ($query) {
            $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                }
                )
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->with('category')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.grocery.low-stock.index', compact('products'));
    }
}
