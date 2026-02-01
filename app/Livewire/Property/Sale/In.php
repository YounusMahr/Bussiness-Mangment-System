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
    public $amount = 0;
    public $notes = '';

    // Calculated values
    public $totalSalePrice = 0;
    public $totalPaidSoFar = 0;
    public $remainingToReceive = 0;
    public $maxAllowedAmount = 0;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ];
    }

    public function mount(PlotSale $sale)
    {
        $this->saleId = $sale->id;
        $this->sale = $sale;
        $this->date = now()->format('Y-m-d');
        $this->updateAmounts();
    }

    public function updatedAmount()
    {
        $this->updateAmounts();
    }

    public function updateAmounts()
    {
        $this->totalSalePrice = (float)($this->sale->total_sale_price ?? 0);
        $this->totalPaidSoFar = (float)($this->sale->paid ?? 0);
        $this->remainingToReceive = max($this->totalSalePrice - $this->totalPaidSoFar, 0);
        $this->maxAllowedAmount = $this->remainingToReceive;

        $amount = (float)($this->amount ?? 0);
        if ($amount > $this->maxAllowedAmount && $this->maxAllowedAmount > 0) {
            $this->addError('amount', __('messages.payment_cannot_exceed_remaining') . ' Rs ' . number_format($this->maxAllowedAmount, 2));
        }
    }

    public function save()
    {
        $this->updateAmounts();
        $amount = (float)($this->amount ?? 0);

        if ($amount <= 0) {
            $this->addError('amount', __('messages.please_enter_valid_amount'));
            return;
        }

        if ($amount > $this->maxAllowedAmount) {
            $this->addError('amount', __('messages.payment_cannot_exceed_remaining') . ' Rs ' . number_format($this->maxAllowedAmount, 2));
            return;
        }

        $this->validate();

        $sale = PlotSale::findOrFail($this->saleId);
        $oldTotalSalePrice = (float)($sale->total_sale_price ?? 0);
        $oldPaid = (float)($sale->paid ?? 0);
        $oldRemaining = (float)($sale->remaining ?? 0);
        $newPaid = $oldPaid + $amount;
        $newRemaining = max($oldRemaining - $amount, 0);

        PlotSaleTransaction::create([
            'plot_sale_id' => $this->saleId,
            'date' => $this->date,
            'type' => 'sale-in',
            'installment_no' => null,
            'installment_amount' => $amount,
            'paid_amount' => $amount,
            'payment_amount' => $amount,
            'total_sale_price_before' => $oldTotalSalePrice,
            'paid_before' => $oldPaid,
            'remaining_before' => $oldRemaining,
            'total_sale_price_after' => $oldTotalSalePrice,
            'paid_after' => $newPaid,
            'remaining_after' => $newRemaining,
            'notes' => $this->notes ?: __('messages.payment_received') . ': Rs ' . number_format($amount, 2),
        ]);

        $sale->update([
            'paid' => $newPaid,
            'remaining' => $newRemaining,
            'status' => $newRemaining <= 0 ? 'paid' : 'remaining',
        ]);

        session()->flash('message', __('messages.credit_recorded_successfully') . ' Rs ' . number_format($amount, 2));
        return $this->redirectRoute('property.sale.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.sale.in')
            ->title(__('messages.record_credit') . ' - ' . __('messages.plot_sale'));
    }
}
