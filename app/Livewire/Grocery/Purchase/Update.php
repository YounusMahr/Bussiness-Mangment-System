<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use App\Models\StockPurchaseTransaction;
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
        
        // Store old values before update
        $oldTotalStock = $purchase->total_stock;
        $oldGivenStock = $purchase->given_stock;
        $oldRemainingStock = $purchase->remaining_stock;
        $oldGoodsTotalPrice = $purchase->goods_total_price ?? 0;
        $oldPaid = $purchase->paid ?? 0;
        $oldRemaining = $purchase->remaining ?? 0;
        $oldInterest = $purchase->interest ?? 0;
        $oldTotalRemaining = $purchase->total_remaining ?? 0;
        
        // Calculate differences
        $stockDifference = $this->total_stock - $oldTotalStock;
        $givenStockDifference = $this->given_stock - $oldGivenStock;
        $remainingStockDifference = $this->remaining_stock - $oldRemainingStock;
        
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

        // Create transaction record for the edit if there are significant changes
        if (abs($stockDifference) > 0.01 || abs($givenStockDifference) > 0.01 || abs($remainingStockDifference) > 0.01) {
            // Determine transaction type based on changes
            // If stock increased, it's a debit (stock-in)
            // If stock decreased or payment made, it's a credit (stock-out)
            if ($stockDifference > 0) {
                // Debit: New stock added
                StockPurchaseTransaction::create([
                    'stock_purchase_id' => $purchase->id,
                    'date' => now()->format('Y-m-d'),
                    'type' => 'stock-in',
                    'new_goods_name' => $this->goods_name,
                    'new_goods_total_price' => 0, // No price change in stock-only edit
                    'new_paid' => 0,
                    'new_interest' => 0,
                    'new_total_stock' => abs($stockDifference),
                    'new_given_stock' => abs($givenStockDifference),
                    'total_stock_before' => $oldTotalStock,
                    'remaining_stock_before' => $oldRemainingStock,
                    'goods_total_price_before' => $oldGoodsTotalPrice,
                    'paid_before' => $oldPaid,
                    'remaining_before' => $oldRemaining,
                    'interest_before' => $oldInterest,
                    'total_remaining_before' => $oldTotalRemaining,
                    'total_stock_after' => $this->total_stock,
                    'remaining_stock_after' => $this->remaining_stock,
                    'goods_total_price_after' => $oldGoodsTotalPrice,
                    'paid_after' => $oldPaid,
                    'remaining_after' => $oldRemaining,
                    'interest_after' => $oldInterest,
                    'total_remaining_after' => $oldTotalRemaining,
                    'notes' => $this->notes ?: 'Stock purchase record updated',
                ]);
            } elseif ($givenStockDifference > 0) {
                // Credit: Stock returned
                StockPurchaseTransaction::create([
                    'stock_purchase_id' => $purchase->id,
                    'date' => now()->format('Y-m-d'),
                    'type' => 'stock-out',
                    'return_stock' => abs($givenStockDifference),
                    'return_payment' => 0, // No payment change in stock-only edit
                    'total_stock_before' => $oldTotalStock,
                    'remaining_stock_before' => $oldRemainingStock,
                    'goods_total_price_before' => $oldGoodsTotalPrice,
                    'paid_before' => $oldPaid,
                    'remaining_before' => $oldRemaining,
                    'interest_before' => $oldInterest,
                    'total_remaining_before' => $oldTotalRemaining,
                    'total_stock_after' => $this->total_stock,
                    'remaining_stock_after' => $this->remaining_stock,
                    'goods_total_price_after' => $oldGoodsTotalPrice,
                    'paid_after' => $oldPaid,
                    'remaining_after' => $oldRemaining,
                    'interest_after' => $oldInterest,
                    'total_remaining_after' => $oldTotalRemaining,
                    'notes' => $this->notes ?: 'Stock purchase record updated - stock adjustment',
                ]);
            }
        }

        session()->flash('message', 'Stock purchase updated successfully!');
        return $this->redirectRoute('purchases.bulk', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.purchase.update')
            ->title('Edit Stock Purchase');
    }
}
