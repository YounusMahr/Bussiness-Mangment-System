<?php

namespace App\Livewire\Property\Purchase;

use App\Models\PlotPurchase;
use App\Models\PlotPurchaseTransaction;
use Livewire\Component;

class In extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $purchase;
    public $date;
    public $installment_no = '';
    public $installment_amount = 0;
    public $paid_amount = 0;
    public $notes = '';

    // Calculated values
    public $remaining = 0;
    public $total = 0;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'installment_no' => 'required|string|max:255',
            'installment_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function mount(PlotPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->purchase = $purchase;
        $this->date = now()->format('Y-m-d');
        $this->calculateAmounts();
    }

    public function updatedInstallmentAmount()
    {
        $this->calculateAmounts();
    }

    public function updatedPaidAmount()
    {
        $this->calculateAmounts();
    }

    public function calculateAmounts()
    {
        $installmentAmount = (float)($this->installment_amount ?? 0);
        $paidAmount = (float)($this->paid_amount ?? 0);
        
        // Remaining = Installment Amount - Paid Amount
        $this->remaining = max($installmentAmount - $paidAmount, 0);
        
        // Total = Installment Amount
        $this->total = $installmentAmount;
    }

    public function save()
    {
        $this->validate();
        $this->calculateAmounts();

        $purchase = PlotPurchase::findOrFail($this->purchaseId);
        
        // Store old values
        $oldPlotPrice = $purchase->plot_price ?? 0;
        
        // Create transaction record (Credit - payment received)
        // For Khata: Credit = Money received, use installment_amount as the transaction amount
        PlotPurchaseTransaction::create([
            'plot_purchase_id' => $this->purchaseId,
            'date' => $this->date,
            'type' => 'purchase-in',
            'installment_no' => $this->installment_no,
            'installment_amount' => $this->installment_amount, // Main transaction amount
            'paid_amount' => $this->paid_amount, // Amount actually paid/received
            'plot_price_before' => $oldPlotPrice,
            'plot_price_after' => $oldPlotPrice, // Plot price doesn't change on payment
            'notes' => $this->notes ?: 'Credit transaction - Payment received: Rs ' . number_format($this->installment_amount, 2),
        ]);
        
        session()->flash('message', 'Credit transaction recorded successfully! Installment #' . $this->installment_no . ': Amount: Rs ' . number_format($this->installment_amount, 2) . ', Paid: Rs ' . number_format($this->paid_amount, 2) . ', Remaining: Rs ' . number_format($this->remaining, 2));
        return $this->redirectRoute('property.purchase.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.purchase.in')
            ->title('Record Credit - Property Purchase');
    }
}
