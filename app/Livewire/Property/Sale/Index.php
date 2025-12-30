<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotSale;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

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
        $sale = PlotSale::find($id);
        if ($sale) {
            $sale->delete();
            session()->flash('message', 'Plot sale deleted successfully!');
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function render()
    {
        $sales = PlotSale::with(['plotPurchase', 'plotPurchase.customer'])
            ->when($this->search, function ($query) {
                $query->where('customer_name', 'like', "%{$this->search}%")
                      ->orWhere('customer_number', 'like', "%{$this->search}%")
                      ->orWhereHas('plotPurchase', function($q) {
                          $q->where('plot_area', 'like', "%{$this->search}%")
                            ->orWhere('location', 'like', "%{$this->search}%");
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.property.sale.index', compact('sales'))
            ->title('Plot Sales');
    }
}
