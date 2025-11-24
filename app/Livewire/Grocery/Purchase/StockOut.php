<?php

namespace App\Livewire\Grocery\Purchase;

use App\Models\StockPurchase;
use App\Models\StockPurchaseTransaction;
use Livewire\Component;

class StockOut extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $purchase;
    public $date;
    public $current_remaining_stock = 0;
    public $current_remaining = 0;
    public $current_total_remaining = 0;
    
    // Stock out fields
    public $return_stock = 0;
    public $return_payment = 0;
    public $notes = '';

    // Calculated new values
    public $new_remaining_stock = 0;
    public $new_remaining = 0;
    public $new_total_remaining = 0;
    public $new_paid = 0;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'return_stock' => ['required', 'numeric', 'min:0', 'max:' . $this->current_remaining_stock],
            'return_payment' => ['required', 'numeric', 'min:0', 'max:' . $this->current_total_remaining],
            'notes' => 'nullable|string',
        ];
    }

    public function mount(StockPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->purchase = $purchase;
        $this->date = now()->format('Y-m-d');
        
        // Load current values
        $this->current_remaining_stock = $purchase->remaining_stock;
        $this->current_remaining = $purchase->remaining;
        $this->current_total_remaining = $purchase->total_remaining;
        
        $this->calculateNewAmounts();
    }

    public function updatedReturnStock()
    {
        $this->calculateNewAmounts();
    }

    public function updatedReturnPayment()
    {
        $this->calculateNewAmounts();
    }

    public function calculateNewAmounts()
    {
        // Stock calculation
        $stockReturn = min((float)($this->return_stock ?? 0), $this->current_remaining_stock);
        $this->new_remaining_stock = max($this->current_remaining_stock - $stockReturn, 0);
        
        // Payment calculation
        $paymentReturn = min((float)($this->return_payment ?? 0), $this->current_total_remaining);
        $this->new_paid = $this->purchase->paid + $paymentReturn;
        
        // Calculate new remaining (reduce from remaining first, then from interest)
        $paymentLeft = $paymentReturn;
        $newRemaining = $this->current_remaining;
        $newInterest = $this->purchase->interest;
        
        // First reduce from remaining (principal)
        if ($paymentLeft > 0 && $newRemaining > 0) {
            $reduceFromRemaining = min($paymentLeft, $newRemaining);
            $newRemaining = max($newRemaining - $reduceFromRemaining, 0);
            $paymentLeft -= $reduceFromRemaining;
        }
        
        // Then reduce from interest if payment left
        if ($paymentLeft > 0 && $newInterest > 0) {
            $newInterest = max($newInterest - $paymentLeft, 0);
        }
        
        $this->new_remaining = $newRemaining;
        $this->new_total_remaining = $newRemaining + $newInterest;
    }

    public function save()
    {
        $this->validate();
        $this->calculateNewAmounts();

        // Update the purchase record
        $purchase = StockPurchase::findOrFail($this->purchaseId);
        
        // Calculate new interest from the calculated values
        $newInterest = max($this->new_total_remaining - $this->new_remaining, 0);
        
        // Create transaction record before updating
        StockPurchaseTransaction::create([
            'stock_purchase_id' => $this->purchaseId,
            'date' => $this->date,
            'type' => 'stock-out',
            'return_stock' => $this->return_stock,
            'return_payment' => $this->return_payment,
            'total_stock_before' => $purchase->total_stock,
            'remaining_stock_before' => $purchase->remaining_stock,
            'goods_total_price_before' => $purchase->goods_total_price,
            'paid_before' => $purchase->paid,
            'remaining_before' => $purchase->remaining,
            'interest_before' => $purchase->interest,
            'total_remaining_before' => $purchase->total_remaining,
            'total_stock_after' => $purchase->total_stock,
            'remaining_stock_after' => $this->new_remaining_stock,
            'goods_total_price_after' => $purchase->goods_total_price,
            'paid_after' => $this->new_paid,
            'remaining_after' => $this->new_remaining,
            'interest_after' => $newInterest,
            'total_remaining_after' => $this->new_total_remaining,
            'notes' => $this->notes,
        ]);
        
        $purchase->update([
            'given_stock' => $purchase->given_stock + $this->return_stock,
            'remaining_stock' => $this->new_remaining_stock,
            'paid' => $this->new_paid,
            'remaining' => $this->new_remaining,
            'interest' => $newInterest,
            'total_remaining' => $this->new_total_remaining,
            'status' => $this->new_remaining_stock <= 0 ? 'complete' : 'remaining',
            'notes' => $this->notes ? ($purchase->notes ? $purchase->notes . "\n\nStock Out on " . $this->date . " (Stock: " . number_format($this->return_stock, 2) . ", Payment: Rs " . number_format($this->return_payment, 2) . "): " . $this->notes : "Stock Out on " . $this->date . " (Stock: " . number_format($this->return_stock, 2) . ", Payment: Rs " . number_format($this->return_payment, 2) . "): " . $this->notes) : $purchase->notes,
        ]);

        session()->flash('message', 'Stock out processed successfully!');
        return $this->redirectRoute('purchases.bulk', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.purchase.stock-out')
            ->title('Process Stock Out');
    }
}
