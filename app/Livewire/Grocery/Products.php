<?php

namespace App\Livewire\Grocery;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $showModal = false;
    public $editingProduct = null;
    public $confirmingDeleteId = null;

    // Form fields
    public $name = '';
    public $sku = '';
    public $description = '';
    public $quantity = 0;
    public $price = 0;
    public $cost = 0;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku',
        'description' => 'nullable|string',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'cost' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
    ];

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

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Product $product)
    {
        $this->editingProduct = $product;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->quantity = $product->quantity;
        $this->price = $product->price;
        $this->cost = $product->cost;
        $this->is_active = $product->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editingProduct) {
            $this->rules['sku'] = 'required|string|max:255|unique:products,sku,' . $this->editingProduct->id;
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'cost' => $this->cost,
            'is_active' => $this->is_active,
        ];

        if ($this->editingProduct) {
            $this->editingProduct->update($data);
            session()->flash('message', 'Product updated successfully!');
        }
        else {
            Product::create($data);
            session()->flash('message', 'Product created successfully!');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function delete(Product $product)
    {
        if ($this->confirmingDeleteId != $product->id)
            return;
        $product->delete();
        session()->flash('message', 'Product deleted successfully!');
        $this->confirmingDeleteId = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingProduct = null;
        $this->name = '';
        $this->sku = '';
        $this->description = '';
        $this->quantity = 0;
        $this->price = 0;
        $this->cost = 0;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function printTable()
    {
        $this->dispatch('print-table');
    }

    public function render()
    {
        $products = Product::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.grocery.products', compact('products'));
    }
}
