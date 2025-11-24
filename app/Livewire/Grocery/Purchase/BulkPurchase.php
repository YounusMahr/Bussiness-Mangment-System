<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use Livewire\Component;
use Livewire\WithPagination;

class BulkPurchase extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    // Form properties
    public $showModal = false;
    public $editingPurchase = null;
    public $date;
    public $seller_name = '';
    public $contact = '';
    public $total_stock = 0;
    public $given_stock = 0;
    public $remaining_stock = 0;
    public $status = 'remaining';
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'seller_name' => 'required|string|max:255',
        'contact' => 'nullable|string|max:255',
        'total_stock' => 'required|numeric|min:0',
        'given_stock' => 'required|numeric|min:0',
        'remaining_stock' => 'nullable|numeric|min:0',
        'status' => 'required|in:remaining,complete',
        'notes' => 'nullable|string',
    ];

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

    public function create()
    {
        $this->resetForm();
        $this->date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->editingPurchase = null;
    }

    public function edit($id)
    {
        $purchase = StockPurchase::findOrFail($id);
        $this->editingPurchase = $purchase;
        $this->date = $purchase->date->format('Y-m-d');
        $this->seller_name = $purchase->seller_name;
        $this->contact = $purchase->contact ?? '';
        $this->total_stock = $purchase->total_stock;
        $this->given_stock = $purchase->given_stock;
        $this->remaining_stock = $purchase->remaining_stock;
        $this->status = $purchase->status;
        $this->notes = $purchase->notes ?? '';
        $this->showModal = true;
    }

    public function updatedTotalStock()
    {
        $this->calculateRemaining();
    }

    public function updatedGivenStock()
    {
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $total = (float)($this->total_stock ?? 0);
        $given = (float)($this->given_stock ?? 0);
        $this->remaining_stock = max($total - $given, 0);
        
        // Auto-update status
        if ($this->remaining_stock <= 0) {
            $this->status = 'complete';
        } else {
            $this->status = 'remaining';
        }
    }

    public function save()
    {
        $this->validate();
        $this->calculateRemaining();

        if ($this->editingPurchase) {
            $this->editingPurchase->update([
                'date' => $this->date,
                'seller_name' => $this->seller_name,
                'contact' => $this->contact ?: null,
                'total_stock' => $this->total_stock,
                'given_stock' => $this->given_stock,
                'remaining_stock' => $this->remaining_stock,
                'status' => $this->status,
                'notes' => $this->notes ?: null,
            ]);
            session()->flash('message', 'Stock purchase updated successfully!');
        } else {
            StockPurchase::create([
                'date' => $this->date,
                'goods_name' => 'Stock Purchase', // Default goods name
                'seller_name' => $this->seller_name,
                'contact' => $this->contact ?: null,
                'total_stock' => $this->total_stock,
                'given_stock' => $this->given_stock,
                'remaining_stock' => $this->remaining_stock,
                'status' => $this->status,
                'notes' => $this->notes ?: null,
            ]);
            session()->flash('message', 'Stock purchase created successfully!');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingPurchase = null;
        $this->date = '';
        $this->seller_name = '';
        $this->contact = '';
        $this->total_stock = 0;
        $this->given_stock = 0;
        $this->remaining_stock = 0;
        $this->status = 'remaining';
        $this->notes = '';
        $this->resetErrorBag();
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
        $purchase = StockPurchase::find($id);
        if ($purchase) {
            $purchase->delete();
            session()->flash('message', 'Stock purchase deleted successfully!');
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
        $purchases = StockPurchase::when($this->search, function ($query) {
                $query->where('seller_name', 'like', "%{$this->search}%")
                      ->orWhere('contact', 'like', "%{$this->search}%")
                      ->orWhere('notes', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.grocery.purchase.bulk-purchase', compact('purchases'))
            ->title('Stock Purchases');
    }
}
