<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use App\Models\StockPurchaseTransaction;
use Livewire\Component;

class StockIn extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $purchase;
    public $date;
    public $current_total_stock = 0;
    public $current_given_stock = 0;
    public $current_remaining_stock = 0;
    public $current_goods_total_price = 0;
    public $current_paid = 0;
    public $current_remaining = 0;
    public $current_interest = 0;
    public $current_total_remaining = 0;
    
    // New stock in fields
    public $new_goods_name = '';
    public $new_goods_total_price = 0;
    public $new_paid = 0;
    public $new_remaining = 0;
    public $new_interest = 0;
    public $new_total_remaining = 0;
    public $new_total_stock = 0;
    public $new_given_stock = 0;
    public $new_remaining_stock = 0;
    public $time_period = '';
    public $due_date;
    public $notes = '';

    // Calculated new totals
    public $new_total_stock_final = 0;
    public $new_given_stock_final = 0;
    public $new_remaining_stock_final = 0;
    public $new_goods_total_price_final = 0;
    public $new_paid_final = 0;
    public $new_remaining_final = 0;
    public $new_interest_final = 0;
    public $new_total_remaining_final = 0;

    protected $rules = [
        'date' => 'required|date',
        'new_goods_name' => 'nullable|string|max:255',
        'new_goods_total_price' => 'required|numeric|min:0',
        'new_paid' => 'required|numeric|min:0',
        'new_interest' => 'nullable|numeric|min:0',
        'new_total_stock' => 'required|numeric|min:0',
        'new_given_stock' => 'required|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    public function mount(StockPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->purchase = $purchase;
        $this->date = now()->format('Y-m-d');
        
        // Load current values
        $this->current_total_stock = $purchase->total_stock;
        $this->current_given_stock = $purchase->given_stock;
        $this->current_remaining_stock = $purchase->remaining_stock;
        $this->current_goods_total_price = $purchase->goods_total_price;
        $this->current_paid = $purchase->paid;
        $this->current_remaining = $purchase->remaining;
        $this->current_interest = $purchase->interest;
        $this->current_total_remaining = $purchase->total_remaining;
        
        $this->calculateNewTotals();
    }

    public function updatedNewGoodsTotalPrice()
    {
        $this->calculateFinancials();
        $this->calculateNewTotals();
    }

    public function updatedNewPaid()
    {
        $this->calculateFinancials();
        $this->calculateNewTotals();
    }

    public function updatedNewInterest()
    {
        $this->calculateFinancials();
        $this->calculateNewTotals();
    }

    public function updatedNewTotalStock()
    {
        $this->calculateStock();
        $this->calculateNewTotals();
    }

    public function updatedNewGivenStock()
    {
        $this->calculateStock();
        $this->calculateNewTotals();
    }

    public function calculateFinancials()
    {
        $totalPrice = (float)($this->new_goods_total_price ?? 0);
        $paidAmount = (float)($this->new_paid ?? 0);
        $interestAmount = (float)($this->new_interest ?? 0);
        
        $this->new_remaining = max($totalPrice - $paidAmount, 0);
        $this->new_total_remaining = $this->new_remaining + $interestAmount;
    }

    public function calculateStock()
    {
        $total = (float)($this->new_total_stock ?? 0);
        $given = (float)($this->new_given_stock ?? 0);
        $this->new_remaining_stock = max($total - $given, 0);
    }

    public function calculateNewTotals()
    {
        // Stock totals
        $this->new_total_stock_final = $this->current_total_stock + (float)($this->new_total_stock ?? 0);
        $this->new_given_stock_final = $this->current_given_stock + (float)($this->new_given_stock ?? 0);
        $this->new_remaining_stock_final = $this->new_total_stock_final - $this->new_given_stock_final;
        
        // Financial totals
        $this->new_goods_total_price_final = $this->current_goods_total_price + (float)($this->new_goods_total_price ?? 0);
        $this->new_paid_final = $this->current_paid + (float)($this->new_paid ?? 0);
        $this->new_remaining_final = $this->new_goods_total_price_final - $this->new_paid_final;
        $this->new_interest_final = $this->current_interest + (float)($this->new_interest ?? 0);
        $this->new_total_remaining_final = $this->new_remaining_final + $this->new_interest_final;
    }

    public function save()
    {
        $this->validate();
        $this->calculateFinancials();
        $this->calculateStock();
        $this->calculateNewTotals();

        // Update the purchase record
        $purchase = StockPurchase::findOrFail($this->purchaseId);
        
        // Update goods name if provided
        $goodsName = $this->new_goods_name ? $purchase->goods_name . ', ' . $this->new_goods_name : $purchase->goods_name;
        
        // Create transaction record before updating
        StockPurchaseTransaction::create([
            'stock_purchase_id' => $this->purchaseId,
            'date' => $this->date,
            'type' => 'stock-in',
            'new_goods_name' => $this->new_goods_name,
            'new_goods_total_price' => $this->new_goods_total_price,
            'new_paid' => $this->new_paid,
            'new_interest' => $this->new_interest,
            'new_total_stock' => $this->new_total_stock,
            'new_given_stock' => $this->new_given_stock,
            'total_stock_before' => $purchase->total_stock,
            'remaining_stock_before' => $purchase->remaining_stock,
            'goods_total_price_before' => $purchase->goods_total_price,
            'paid_before' => $purchase->paid,
            'remaining_before' => $purchase->remaining,
            'interest_before' => $purchase->interest,
            'total_remaining_before' => $purchase->total_remaining,
            'total_stock_after' => $this->new_total_stock_final,
            'remaining_stock_after' => $this->new_remaining_stock_final,
            'goods_total_price_after' => $this->new_goods_total_price_final,
            'paid_after' => $this->new_paid_final,
            'remaining_after' => $this->new_remaining_final,
            'interest_after' => $this->new_interest_final,
            'total_remaining_after' => $this->new_total_remaining_final,
            'notes' => $this->notes,
        ]);
        
        $purchase->update([
            'goods_name' => $goodsName,
            'total_stock' => $this->new_total_stock_final,
            'given_stock' => $this->new_given_stock_final,
            'remaining_stock' => $this->new_remaining_stock_final,
            'goods_total_price' => $this->new_goods_total_price_final,
            'paid' => $this->new_paid_final,
            'remaining' => $this->new_remaining_final,
            'interest' => $this->new_interest_final,
            'total_remaining' => $this->new_total_remaining_final,
            'time_period' => $this->time_period ?: $purchase->time_period,
            'due_date' => $this->due_date ?: $purchase->due_date,
            'status' => $this->new_remaining_stock_final <= 0 ? 'complete' : 'remaining',
            'notes' => $this->notes ? ($purchase->notes ? $purchase->notes . "\n\nStock In on " . $this->date . ": " . $this->notes : "Stock In on " . $this->date . ": " . $this->notes) : $purchase->notes,
        ]);

        session()->flash('message', 'New stock added successfully!');
        return $this->redirectRoute('purchases.bulk', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.purchase.stock-in')
            ->title('Add Stock In');
    }
}
