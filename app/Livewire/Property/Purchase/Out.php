<?php

namespace App\Livewire\Property\Purchase;

use App\Models\PlotPurchase;
use App\Models\PlotPurchaseTransaction;
use Livewire\Component;

class Out extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $purchase;
    public $date;
    public $amount = 0;
    public $notes = '';

    public $totalPlotCost = 0;
    public $totalPaidSoFar = 0;
    public $remainingToPay = 0;
    public $maxAllowedAmount = 0;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ];
    }

    protected function messages()
    {
        return [
            'amount.max' => 'Payment amount cannot exceed remaining balance (Rs ' . number_format($this->maxAllowedAmount, 2) . ').',
        ];
    }

    public function mount(PlotPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->purchase = $purchase;
        $this->date = now()->format('Y-m-d');
        $this->updateAmounts();
    }

    public function updatedAmount()
    {
        $this->updateAmounts();
    }

    public function updateAmounts()
    {
        $this->totalPlotCost = (float)($this->purchase->plot_price ?? 0);
        $this->totalPaidSoFar = (float) PlotPurchaseTransaction::where('plot_purchase_id', $this->purchaseId)
            ->where('type', 'purchase-out')
            ->sum('payment_amount');
        $this->remainingToPay = max($this->totalPlotCost - $this->totalPaidSoFar, 0);
        $this->maxAllowedAmount = $this->remainingToPay;

        // Add validation for amount
        $amount = (float)($this->amount ?? 0);
        if ($amount > $this->maxAllowedAmount && $this->maxAllowedAmount > 0) {
            $this->addError('amount', 'Payment cannot exceed remaining balance: Rs ' . number_format($this->maxAllowedAmount, 2));
        }
    }

    public function save()
    {
        $this->updateAmounts();
        $amount = (float)($this->amount ?? 0);

        if ($amount <= 0) {
            $this->addError('amount', 'Please enter a valid payment amount.');
            return;
        }

        if ($amount > $this->maxAllowedAmount) {
            $this->addError('amount', 'Payment cannot exceed remaining balance: Rs ' . number_format($this->maxAllowedAmount, 2));
            return;
        }

        $this->validate();

        $purchase = PlotPurchase::findOrFail($this->purchaseId);
        $oldPlotPrice = (float)($purchase->plot_price ?? 0);

        PlotPurchaseTransaction::create([
            'plot_purchase_id' => $this->purchaseId,
            'date' => $this->date,
            'type' => 'purchase-out',
            'installment_no' => null,
            'installment_amount' => $amount,
            'paid_amount' => $amount,
            'payment_amount' => $amount,
            'plot_price_before' => $oldPlotPrice,
            'plot_price_after' => $oldPlotPrice,
            'notes' => $this->notes ?: 'Payment (Debit): Rs ' . number_format($amount, 2),
        ]);

        session()->flash('message', 'Payment recorded successfully! Rs ' . number_format($amount, 2));
        return $this->redirectRoute('property.purchase.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.purchase.out')
            ->title('Record Payment (Debit) - Plot Purchase');
    }
}
