<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotPurchase;
use App\Models\PlotSale;
use Livewire\Component;

class Edit extends Component
{
    protected $layout = 'layouts.app';

    public $saleId;
    public $date;
    public $plot_purchase_id = '';
    public $customer_name = '';
    public $customer_number = '';
    public $installments = '';
    public $interest = 0;
    public $total_sale_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $time_period = '';
    public $status = 'remaining';
    public $plotPurchases = [];

    protected $rules = [
        'date' => 'required|date',
        'plot_purchase_id' => 'required|exists:plot_purchases,id',
        'customer_name' => 'required|string|max:255',
        'customer_number' => 'required|string|max:255',
        'installments' => 'nullable|string',
        'interest' => 'nullable|numeric|min:0',
        'total_sale_price' => 'required|numeric|min:0',
        'paid' => 'required|numeric|min:0',
        'remaining' => 'nullable|numeric|min:0',
        'time_period' => 'nullable|string|max:255',
        'status' => 'required|in:paid,remaining',
    ];

    public function mount(PlotSale $sale)
    {
        $this->saleId = $sale->id;
        $this->date = $sale->date->format('Y-m-d');
        $this->plot_purchase_id = $sale->plot_purchase_id;
        $this->customer_name = $sale->customer_name;
        $this->customer_number = $sale->customer_number;
        $this->installments = $sale->installments ?? '';
        $this->interest = $sale->interest ?? 0;
        $this->total_sale_price = $sale->total_sale_price;
        $this->paid = $sale->paid;
        $this->remaining = $sale->remaining;
        $this->time_period = $sale->time_period ?? '';
        $this->status = $sale->status;
        
        $this->plotPurchases = PlotPurchase::orderBy('plot_area', 'asc')->get();
    }

    public function updatedTotalSalePrice()
    {
        $this->calculateRemaining();
    }

    public function updatedPaid()
    {
        $this->calculateRemaining();
    }

    public function updatedInterest()
    {
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $total = (float)($this->total_sale_price ?? 0);
        $paidAmount = (float)($this->paid ?? 0);
        $interestAmount = (float)($this->interest ?? 0);
        $this->remaining = max(($total + $interestAmount) - $paidAmount, 0);
        
        // Auto-update status
        if ($this->remaining <= 0) {
            $this->status = 'paid';
        } else {
            $this->status = 'remaining';
        }
    }

    public function update()
    {
        $this->validate();
        $this->calculateRemaining();

        $sale = PlotSale::findOrFail($this->saleId);
        $sale->update([
            'date' => $this->date,
            'plot_purchase_id' => $this->plot_purchase_id,
            'customer_name' => $this->customer_name,
            'customer_number' => $this->customer_number,
            'installments' => $this->installments ?: null,
            'interest' => $this->interest ?: null,
            'total_sale_price' => $this->total_sale_price,
            'paid' => $this->paid,
            'remaining' => $this->remaining,
            'time_period' => $this->time_period ?: null,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Plot sale updated successfully!');
        return $this->redirectRoute('property.sale.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.sale.edit')
            ->title('Edit Plot Sale');
    }
}
