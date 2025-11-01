<?php

namespace App\Livewire\Grocery\Udaar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Udaar;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'buy_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $viewingUdaarId = null;

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
        return $this->viewingUdaarId ? Udaar::find($this->viewingUdaarId) : null;
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

    public function render()
    {
        $udaars = Udaar::when($this->search, function ($query) {
                $query->where('customer_name', 'like', "%{$this->search}%")
                      ->orWhere('customer_number', 'like', "%{$this->search}%")
                      ->orWhere('notes', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.grocery.udaar.index', compact('udaars'));
    }
}
