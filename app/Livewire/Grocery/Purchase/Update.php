<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use Livewire\Component;

class Update extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $date;
    public $goods_name = '';
    public $seller_name = '';
    public $contact = '';
    public $total_stock = 0;
    public $given_stock = 0;
    public $remaining_stock = 0;
    public $time_period = '';
    public $due_date;
    public $status = 'remaining';
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'goods_name' => 'required|string|max:255',
        'seller_name' => 'required|string|max:255',
        'contact' => 'nullable|string|max:255',
        'total_stock' => 'required|numeric|min:0',
        'given_stock' => 'required|numeric|min:0',
        'remaining_stock' => 'nullable|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'status' => 'required|in:remaining,complete',
        'notes' => 'nullable|string',
    ];

    public function mount(StockPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->date = $purchase->date->format('Y-m-d');
        $this->goods_name = $purchase->goods_name;
        $this->seller_name = $purchase->seller_name;
        $this->contact = $purchase->contact ?? '';
        $this->total_stock = $purchase->total_stock;
        $this->given_stock = $purchase->given_stock;
        $this->remaining_stock = $purchase->remaining_stock;
        $this->time_period = $purchase->time_period ?? '';
        $this->due_date = $purchase->due_date ? $purchase->due_date->format('Y-m-d') : null;
        $this->status = $purchase->status;
        $this->notes = $purchase->notes ?? '';
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

    public function update()
    {
        $this->validate();
        $this->calculateRemaining();

        $purchase = StockPurchase::findOrFail($this->purchaseId);
        $purchase->update([
            'date' => $this->date,
            'goods_name' => $this->goods_name,
            'seller_name' => $this->seller_name,
            'contact' => $this->contact ?: null,
            'total_stock' => $this->total_stock,
            'given_stock' => $this->given_stock,
            'remaining_stock' => $this->remaining_stock,
            'time_period' => $this->time_period,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
        ]);

        session()->flash('message', 'Stock purchase updated successfully!');
        return $this->redirectRoute('purchases.bulk', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.purchase.update')
            ->title('Edit Stock Purchase');
    }
}
