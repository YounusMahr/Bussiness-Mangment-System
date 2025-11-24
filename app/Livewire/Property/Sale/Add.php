<?php

namespace App\Livewire\Property\Sale;

use App\Models\PlotPurchase;
use App\Models\PlotSale;
use Livewire\Component;

class Add extends Component
{
    protected $layout = 'layouts.app';

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

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
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

    public function save()
    {
        $this->validate();
        $this->calculateRemaining();

        PlotSale::create([
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

        session()->flash('message', 'Plot sale created successfully!');
        return $this->redirectRoute('property.sale.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.sale.add')
            ->title('Add Plot Sale');
    }
}
