<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Udaar;
use App\Models\Product;
use App\Models\Installment;
use App\Models\GroceryCashTransaction;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $totalRevenue;
    public $totalSales;
    public $totalUdhaar;
    public $totalProducts;
    public $totalCustomers;
    
    // System breakdowns
    public $groceryRevenue;
    public $grocerySales;
    public $groceryUdhaar;
    public $carInstallmentRevenue;
    public $carInstallmentSales;
    public $carInstallmentRemaining;
    
    // Cash Management
    public $totalCashCredit;
    public $totalCashDebit;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // ========== GROCERY SYSTEM ==========
        // Grocery Revenue: Sum of paid_amount from Sale
        $this->groceryRevenue = Sale::sum('paid_amount') ?? 0;
        
        // Grocery Sales: Count of all Sale records
        $this->grocerySales = Sale::count();
        
        // Grocery Udhaar: Sum of remaining_amount from Udaar
        $this->groceryUdhaar = Udaar::sum('remaining_amount') ?? 0;
        
        // ========== CAR-INSTALLMENT SYSTEM ==========
        // Car Installment Revenue: Sum of paid from Installments
        $this->carInstallmentRevenue = Installment::sum('paid') ?? 0;
        
        // Car Installment Sales: Count of all Installment records
        $this->carInstallmentSales = Installment::count();
        
        // Car Installment Remaining: Sum of remaining from Installments
        $this->carInstallmentRemaining = Installment::sum('remaining') ?? 0;
        
        // ========== COMBINED TOTALS ==========
        // Total Revenue: Grocery + Car Installment
        $this->totalRevenue = $this->groceryRevenue + $this->carInstallmentRevenue;

        // Total Sales: Grocery Sales + Car Installment Sales
        $this->totalSales = $this->grocerySales + $this->carInstallmentSales;

        // Total Udhaar: Grocery Udhaar + Car Installment Remaining
        $this->totalUdhaar = $this->groceryUdhaar + $this->carInstallmentRemaining;

        // Total Products: Count of Product records (Grocery only)
        $this->totalProducts = Product::count();

        // Total Customers: Count of unique customers from both systems
        $allCustomers = collect();
        
        // Grocery customers from Sales
        Sale::whereNotNull('customer_name')->pluck('customer_name')->each(function($name) use ($allCustomers) {
            $allCustomers->push($name);
        });
        
        // Grocery customers from Udaar
        Udaar::whereNotNull('customer_name')->pluck('customer_name')->each(function($name) use ($allCustomers) {
            $allCustomers->push($name);
        });
        
        // Car Installment customers
        Installment::whereHas('customer')->with('customer')->get()->each(function($installment) use ($allCustomers) {
            if ($installment->customer && $installment->customer->name) {
                $allCustomers->push($installment->customer->name);
            }
        });
        
        $this->totalCustomers = $allCustomers->unique()->count();
        
        // ========== CASH MANAGEMENT ==========
        // Total Cash Credit (Cash-In)
        $this->totalCashCredit = (float)(GroceryCashTransaction::where('type', 'cash-in')->sum('return_amount') ?? 0);
        
        // Total Cash Debit (Cash-Out)
        $this->totalCashDebit = (float)(GroceryCashTransaction::where('type', 'cash-out')->sum('returned_amount') ?? 0);
    }

    public function render()
    {
        return view('livewire.finance.index');
    }
}
