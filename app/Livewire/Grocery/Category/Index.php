<?php

namespace App\Livewire\Grocery\Category;

use App\Models\Category as CategoryModel;
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

    public function updatingSearch() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id) {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete() {
        $this->confirmingDeleteId = null;
    }

    public function delete(CategoryModel $category)
    {
        if ($this->confirmingDeleteId != $category->id) return;
        $category->delete();
        session()->flash('message', 'Category deleted successfully!');
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $categories = CategoryModel::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('slug', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.grocery.category.index', compact('categories'));
    }
}
