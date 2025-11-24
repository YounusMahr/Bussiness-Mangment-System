<?php

namespace App\Livewire\Property\Purchase;

use App\Models\PlotPurchase;
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
        $purchase = PlotPurchase::find($id);
        if ($purchase) {
            $purchase->delete();
            session()->flash('message', 'Plot purchase deleted successfully!');
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function render()
    {
        $purchases = PlotPurchase::when($this->search, function ($query) {
                $query->where('plot_area', 'like', "%{$this->search}%")
                      ->orWhere('location', 'like', "%{$this->search}%")
                      ->orWhere('installments', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.property.purchase.index', compact('purchases'))
            ->title('Plot Purchases');
    }
}
