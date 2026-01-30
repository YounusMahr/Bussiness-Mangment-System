<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotSale;
use App\Models\PlotSaleTransaction;
use Livewire\Component;

class Out extends Component
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
        
        // Calculate new values (Debit - payment made/refund)
        $newPaid = max($oldPaid - $this->paid_amount, 0);
        $newRemaining = $oldRemaining + $this->paid_amount;
        
        // Create transaction record (Debit - payment made/refund)
        // For Khata: Debit = Money paid, use installment_amount as payment_amount
        PlotSaleTransaction::create([
            'plot_sale_id' => $this->saleId,
            'date' => $this->date,
            'type' => 'sale-out',
            'installment_no' => $this->installment_no,
            'installment_amount' => $this->installment_amount, // Main transaction amount
            'paid_amount' => $this->paid_amount,
            'payment_amount' => $this->installment_amount, // This is the debit amount (money paid/refunded) - use installment_amount
            'total_sale_price_before' => $oldTotalSalePrice,
            'paid_before' => $oldPaid,
            'remaining_before' => $oldRemaining,
            'total_sale_price_after' => $oldTotalSalePrice,
            'paid_after' => $newPaid,
            'remaining_after' => $newRemaining,
            'notes' => $this->notes ?: 'Debit transaction - Payment made/refund: Rs ' . number_format($this->installment_amount, 2),
        ]);
        
        // Update the sale record
        $sale->update([
            'paid' => $newPaid,
            'remaining' => $newRemaining,
            'status' => $newRemaining > 0 ? 'remaining' : 'paid',
        ]);
        
        session()->flash('message', 'Debit transaction recorded successfully! Installment #' . $this->installment_no . ': Amount: Rs ' . number_format($this->installment_amount, 2) . ', Paid: Rs ' . number_format($this->paid_amount, 2) . ', Remaining: Rs ' . number_format($this->remaining, 2));
        return $this->redirectRoute('property.sale.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.sale.out')
            ->title('Record Debit - Property Sale');
    }
}
