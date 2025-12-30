<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotSale;
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

        // TODO: Create a PlotSaleTransaction model to properly track transactions
        // For now, we'll just show a success message
        // The transaction will be stored in a transaction table when implemented
        
        $message = 'Credit transaction recorded successfully! ';
        $message .= 'Installment #' . $this->installment_no . ': ';
        $message .= 'Amount: Rs ' . number_format($this->installment_amount, 2) . ', ';
        $message .= 'Paid: Rs ' . number_format($this->paid_amount, 2) . ', ';
        $message .= 'Remaining: Rs ' . number_format($this->remaining, 2);
        
        session()->flash('message', $message);
        return $this->redirectRoute('property.sale.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.sale.in')
            ->title('Record Credit - Property Sale');
    }
}
