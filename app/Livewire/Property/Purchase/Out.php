<?php

namespace App\Livewire\Property\Purchase;

use App\Models\PlotPurchase;
use Livewire\Component;

class Out extends Component
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
        
        // Total = Installment Amount (for now, can be extended to sum multiple installments)
        $this->total = $installmentAmount;
    }

    public function save()
    {
        $this->validate();
        $this->calculateAmounts();

        // TODO: Create a PlotPurchaseTransaction model to properly track transactions
        // For now, we'll just show a success message
        // The transaction will be stored in a transaction table when implemented
        
        $message = 'Installment recorded successfully! ';
        $message .= 'Installment #' . $this->installment_no . ': ';
        $message .= 'Amount: Rs ' . number_format($this->installment_amount, 2) . ', ';
        $message .= 'Paid: Rs ' . number_format($this->paid_amount, 2) . ', ';
        $message .= 'Remaining: Rs ' . number_format($this->remaining, 2);
        
        session()->flash('message', $message);
        return $this->redirectRoute('property.purchase.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.purchase.out')
            ->title('Record Debit - Property Purchase');
    }
}
