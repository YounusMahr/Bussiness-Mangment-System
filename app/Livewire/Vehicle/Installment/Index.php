<?php

namespace App\Livewire\Vehicle\Installment;

use App\Models\Installment;
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
        $installment = Installment::find($id);
        if ($installment) {
            $installment->delete();
            session()->flash('message', __('messages.installment_deleted'));
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function render()
    {
        $installments = Installment::with('customer')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('vehicle', 'like', "%{$this->search}%")
                      ->orWhere('model', 'like', "%{$this->search}%")
                      ->orWhere('number', 'like', "%{$this->search}%")
                      ->orWhereHas('customer', function($customerQuery) {
                          $customerQuery->where('name', 'like', "%{$this->search}%")
                                        ->orWhere('number', 'like', "%{$this->search}%");
                      });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.vehicle.installment.index', compact('installments'))
            ->title('Installments');
    }
}
