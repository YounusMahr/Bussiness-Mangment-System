<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Udaar;
use App\Models\Product;
use App\Models\VehicleBooking;
use App\Models\CarRentUdaar;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $totalRevenue;
    public $totalSales;
    public $totalUdhaar;
    public $totalProducts;
    public $totalCustomers;
    public $rentedCars;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Total Revenue: Sum of paid_amount from Sale (grocery) + total_price from VehicleBooking (car-rent)
        $groceryRevenue = Sale::sum('paid_amount') ?? 0;
        $carRentRevenue = VehicleBooking::sum('total_price') ?? 0;
        $this->totalRevenue = $groceryRevenue + $carRentRevenue;

        // Total Sales: Count of all Sale records
        $this->totalSales = Sale::count();

        // Total Udhaar: Sum of remaining_amount from Udaar (grocery) + udaar_amount from CarRentUdaar (car-rent)
        $groceryUdhaar = Udaar::sum('remaining_amount') ?? 0;
        $carRentUdhaar = CarRentUdaar::sum('udaar_amount') ?? 0;
        $this->totalUdhaar = $groceryUdhaar + $carRentUdhaar;

        // Total Products: Count of Product records
        $this->totalProducts = Product::count();

        // Total Customers: Count of unique customers from both systems
        $groceryCustomers = Sale::whereNotNull('customer_name')
            ->distinct('customer_name')
            ->count('customer_name');
        
        $groceryUdhaarCustomers = Udaar::whereNotNull('customer_name')
            ->distinct('customer_name')
            ->count('customer_name');
        
        $carRentCustomers = VehicleBooking::whereNotNull('customer_name')
            ->distinct('customer_name')
            ->count('customer_name');
        
        $carRentUdhaarCustomers = CarRentUdaar::whereNotNull('customer')
            ->distinct('customer')
            ->count('customer');
        
        // Get unique customers across all systems
        $allCustomers = collect();
        
        Sale::whereNotNull('customer_name')->pluck('customer_name')->each(function($name) use ($allCustomers) {
            $allCustomers->push($name);
        });
        
        Udaar::whereNotNull('customer_name')->pluck('customer_name')->each(function($name) use ($allCustomers) {
            $allCustomers->push($name);
        });
        
        VehicleBooking::whereNotNull('customer_name')->pluck('customer_name')->each(function($name) use ($allCustomers) {
            $allCustomers->push($name);
        });
        
        CarRentUdaar::whereNotNull('customer')->pluck('customer')->each(function($name) use ($allCustomers) {
            $allCustomers->push($name);
        });
        
        $this->totalCustomers = $allCustomers->unique()->count();

        // Rented Cars: Count of VehicleBooking records where return_date is null or in the future
        $this->rentedCars = VehicleBooking::where(function($query) {
            $query->whereNull('return_date')
                  ->orWhere('return_date', '>=', now());
        })->count();
    }

    public function render()
    {
        return view('livewire.finance.index');
    }
}
