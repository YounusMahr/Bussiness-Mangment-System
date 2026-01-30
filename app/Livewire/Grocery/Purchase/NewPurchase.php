<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use App\Models\StockPurchaseTransaction;
use Livewire\Component;

class NewPurchase extends Component
{
    protected $layout = 'layouts.app';

    public $date;
    public $goods_name = '';
    public $seller_name = '';
    public $contact = '';
    public $goods_total_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $interest = 0;
    public $total_remaining = 0;
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
        'goods_total_price' => 'required|numeric|min:0',
        'paid' => 'required|numeric|min:0',
        'interest' => 'nullable|numeric|min:0',
        'total_stock' => 'required|numeric|min:0',
        'given_stock' => 'required|numeric|min:0',
        'remaining_stock' => 'nullable|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'status' => 'required|in:remaining,complete',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updatedTotalStock()
    {
        $this->calculateRemaining();
    }

    public function updatedGivenStock()
    {
        $this->calculateRemaining();
    }

    public function updatedGoodsTotalPrice()
    {
        $this->calculateFinancials();
    }

    public function updatedPaid()
    {
        $this->calculateFinancials();
    }

    public function updatedInterest()
    {
        $this->calculateFinancials();
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

    public function calculateFinancials()
    {
        $totalPrice = (float)($this->goods_total_price ?? 0);
        $paidAmount = (float)($this->paid ?? 0);
        $interestAmount = (float)($this->interest ?? 0);
        
        $this->remaining = max($totalPrice - $paidAmount, 0);
        $this->total_remaining = $this->remaining + $interestAmount;
    }

    public function save()
    {
        $this->validate();
        $this->calculateRemaining();
        $this->calculateFinancials();

        $purchase = StockPurchase::create([
            'date' => $this->date,
            'goods_name' => $this->goods_name,
            'seller_name' => $this->seller_name,
            'contact' => $this->contact ?: null,
            'goods_total_price' => $this->goods_total_price,
            'paid' => $this->paid,
            'remaining' => $this->remaining,
            'interest' => $this->interest,
            'total_remaining' => $this->total_remaining,
            'total_stock' => $this->total_stock,
            'given_stock' => $this->given_stock,
            'remaining_stock' => $this->remaining_stock,
            'time_period' => $this->time_period,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
        ]);

        // Create initial transaction record for the new purchase
        StockPurchaseTransaction::create([
            'stock_purchase_id' => $purchase->id,
            'date' => $this->date,
            'type' => 'stock-in',
            'new_goods_name' => $this->goods_name,
            'new_goods_total_price' => $this->goods_total_price,
            'new_paid' => $this->paid,
            'new_interest' => $this->interest ?? 0,
            'new_total_stock' => $this->total_stock,
            'new_given_stock' => $this->given_stock,
            'total_stock_before' => 0,
            'remaining_stock_before' => 0,
            'goods_total_price_before' => 0,
            'paid_before' => 0,
            'remaining_before' => 0,
            'interest_before' => 0,
            'total_remaining_before' => 0,
            'total_stock_after' => $this->total_stock,
            'remaining_stock_after' => $this->remaining_stock,
            'goods_total_price_after' => $this->goods_total_price,
            'paid_after' => $this->paid,
            'remaining_after' => $this->remaining,
            'interest_after' => $this->interest ?? 0,
            'total_remaining_after' => $this->total_remaining,
            'notes' => $this->notes ?: 'Initial stock purchase record created',
        ]);

        session()->flash('message', 'Stock purchase created successfully!');
        return $this->redirectRoute('purchases.bulk', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.purchase.new-purchase')
            ->title('Add Stock Purchase');
    }
}
