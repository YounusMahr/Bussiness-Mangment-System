<?php

namespace App\Livewire\Property\Sale;

use App\Models\Customer;
use App\Models\PlotPurchase;
use App\Models\PlotSale;
use App\Models\PlotSaleTransaction;
use Livewire\Component;

class Add extends Component
{
    protected $layout = 'layouts.app';

    public $date;
    public $plot_purchase_id = '';
    public $customer_id = '';
    public $customer_name = '';
    public $customer_number = '';
    public $installment_no = '';
    public $installment_amount = 0;
    public $paid_amount = 0;
    public $installments = '';
    public $interest = 0;
    public $total_sale_price = 0;
    public $paid = 0;
    public $remaining = 0;
    public $time_period = '';
    public $status = 'remaining';
    public $plotPurchases = [];
    public $customers = [];

    // Calculated values
    public $remaining_calc = 0;
    public $total_calc = 0;

    protected $rules = [
        'date' => 'required|date',
        'plot_purchase_id' => 'required|exists:plot_purchases,id',
        'customer_id' => 'nullable|exists:customers,id',
        'customer_name' => 'required|string|max:255',
        'customer_number' => 'required|string|max:255',
        'installment_no' => 'nullable|string|max:255',
        'installment_amount' => 'nullable|numeric|min:0',
        'paid_amount' => 'nullable|numeric|min:0',
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
        $this->customers = Customer::where('type', 'Plot')->orderBy('name', 'asc')->get();
    }

    public function updatedCustomerId()
    {
        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
            if ($customer) {
                $this->customer_name = $customer->name;
                $this->customer_number = $customer->number;
            }
        }
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
        $this->remaining_calc = max($installmentAmount - $paidAmount, 0);
        
        // Total = Installment Amount
        $this->total_calc = $installmentAmount;
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

        $sale = PlotSale::create([
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

        // Create initial transaction record for the new sale
        // For Khata: Use total_sale_price as the main transaction amount
        PlotSaleTransaction::create([
            'plot_sale_id' => $sale->id,
            'date' => $this->date,
            'type' => 'sale-in', // Credit - initial sale
            'installment_no' => $this->installment_no ?: 'Initial',
            'installment_amount' => $this->total_sale_price, // Main transaction amount for Khata
            'paid_amount' => $this->paid,
            'total_sale_price_before' => 0,
            'paid_before' => 0,
            'remaining_before' => 0,
            'total_sale_price_after' => $this->total_sale_price,
            'paid_after' => $this->paid,
            'remaining_after' => $this->remaining,
            'notes' => 'Initial plot sale record created: Rs ' . number_format($this->total_sale_price, 2),
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
