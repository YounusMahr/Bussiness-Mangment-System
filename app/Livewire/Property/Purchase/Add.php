<?php

namespace App\Livewire\Property\Purchase;

use App\Models\Customer;
use App\Models\PlotPurchase;
use App\Models\PlotPurchaseTransaction;
use Livewire\Component;

class Add extends Component
{
    protected $layout = 'layouts.app';

    public $customer_id = '';
    public $date;
    public $plot_area = '';
    public $plot_price = 0;
    public $installment_no = '';
    public $installment_amount = 0;
    public $paid_amount = 0;
    public $installments = ''; // Keep for backward compatibility/notes
    public $location = '';
    public $customers = [];

    // Calculated values
    public $remaining = 0;
    public $total = 0;

    protected $rules = [
        'customer_id' => 'nullable|exists:customers,id',
        'date' => 'required|date',
        'plot_area' => 'required|string|max:255',
        'plot_price' => 'required|numeric|min:0',
        'installment_no' => 'nullable|string|max:255',
        'installment_amount' => 'nullable|numeric|min:0',
        'paid_amount' => 'nullable|numeric|min:0',
        'installments' => 'nullable|string',
        'location' => 'required|string',
    ];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->customers = Customer::where('type', 'Plot')->orderBy('name', 'asc')->get();
        $this->calculateAmounts();
    }

    public function updatedPlotPrice()
    {
        $this->calculateAmounts();
    }

    public function updatedPaidAmount()
    {
        $this->calculateAmounts();
    }

    public function calculateAmounts()
    {
        $plotPrice = (float)($this->plot_price ?? 0);
        $paidAmount = (float)($this->paid_amount ?? 0);
        
        // Remaining = Plot Price - Paid Amount (total cost minus what's paid)
        $this->remaining = max($plotPrice - $paidAmount, 0);
        
        // Total = Plot Price
        $this->total = $plotPrice;
    }

    public function save()
    {
        $this->validate();

        $purchase = PlotPurchase::create([
            'customer_id' => $this->customer_id ?: null,
            'date' => $this->date,
            'plot_area' => $this->plot_area,
            'plot_price' => $this->plot_price,
            'installments' => $this->installments ?: null,
            'location' => $this->location,
        ]);

        // Create initial payment (debit) transaction if user paid amount at time of purchase
        if ((float)($this->paid_amount ?? 0) > 0) {
            $amount = (float)$this->paid_amount;
            PlotPurchaseTransaction::create([
                'plot_purchase_id' => $purchase->id,
                'date' => $this->date,
                'type' => 'purchase-out', // Debit - payment for plot
                'installment_no' => $this->installment_no ?: '1',
                'installment_amount' => $amount,
                'paid_amount' => $amount,
                'payment_amount' => $amount,
                'plot_price_before' => (float)$this->plot_price,
                'plot_price_after' => (float)$this->plot_price,
                'notes' => 'Initial payment (paid at plot purchase): Rs ' . number_format($amount, 2),
            ]);
        }

        session()->flash('message', 'Plot purchase created successfully!');
        return $this->redirectRoute('property.purchase.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.purchase.add')
            ->title('Add Plot Purchase');
    }
}
