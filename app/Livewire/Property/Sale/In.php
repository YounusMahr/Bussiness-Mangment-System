<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotSale;
use App\Models\PlotSaleTransaction;
use Livewire\Component;

class In extends Component
{
    protected $layout = 'layouts.app';

    public $saleId;
    public $sale;
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

    public function mount(PlotSale $sale)
    {
        $this->saleId = $sale->id;
        $this->sale = $sale;
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

        $sale = PlotSale::findOrFail($this->saleId);
        
        // Store old values
        $oldTotalSalePrice = $sale->total_sale_price ?? 0;
        $oldPaid = $sale->paid ?? 0;
        $oldRemaining = $sale->remaining ?? 0;
        
        // Calculate new values
        $newPaid = $oldPaid + $this->paid_amount;
        $newRemaining = max($oldRemaining - $this->paid_amount, 0);
        
        // Create transaction record (Credit - payment received)
        // For Khata: Credit = Money received, use installment_amount as the transaction amount
        PlotSaleTransaction::create([
            'plot_sale_id' => $this->saleId,
            'date' => $this->date,
            'type' => 'sale-in',
            'installment_no' => $this->installment_no,
            'installment_amount' => $this->installment_amount, // Main transaction amount
            'paid_amount' => $this->paid_amount, // Amount actually paid/received
            'total_sale_price_before' => $oldTotalSalePrice,
            'paid_before' => $oldPaid,
            'remaining_before' => $oldRemaining,
            'total_sale_price_after' => $oldTotalSalePrice,
            'paid_after' => $newPaid,
            'remaining_after' => $newRemaining,
            'notes' => $this->notes ?: 'Credit transaction - Payment received: Rs ' . number_format($this->installment_amount, 2),
        ]);
        
        // Update the sale record
        $sale->update([
            'paid' => $newPaid,
            'remaining' => $newRemaining,
            'status' => $newRemaining <= 0 ? 'paid' : 'remaining',
        ]);
        
        session()->flash('message', 'Credit transaction recorded successfully! Installment #' . $this->installment_no . ': Amount: Rs ' . number_format($this->installment_amount, 2) . ', Paid: Rs ' . number_format($this->paid_amount, 2) . ', Remaining: Rs ' . number_format($this->remaining, 2));
        return $this->redirectRoute('property.sale.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.sale.in')
            ->title('Record Credit - Property Sale');
    }
}
