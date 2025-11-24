<?php

namespace App\Livewire\Finance;

use App\Models\Sale;
use App\Models\Udaar;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CustomersReport extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

    public $filter = 'all'; // all, daily, monthly
    public $selectedDate;
    public $selectedMonth;
    public $search = '';

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedMonth = now()->format('Y-m');
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function updatedSelectedMonth()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function printReport()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        // Get unique customers from Sales (sales table doesn't have customer_number)
        $salesQuery = Sale::whereNotNull('customer_name');
        
        // Apply date filters to sales query
        if ($this->filter === 'daily') {
            $salesQuery->whereDate('date', $this->selectedDate);
        } elseif ($this->filter === 'monthly') {
            $salesQuery->whereYear('date', date('Y', strtotime($this->selectedMonth . '-01')))
                      ->whereMonth('date', date('m', strtotime($this->selectedMonth . '-01')));
        }
        
        $salesCustomers = $salesQuery
            ->select('customer_name', DB::raw('MIN(date) as first_purchase'), DB::raw('MAX(date) as last_purchase'), DB::raw('COUNT(*) as total_sales'), DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('customer_name')
            ->get();

        // Get unique customers from Udaar (udaars table has customer_number)
        $udaarQuery = Udaar::whereNotNull('customer_name');
        
        // Apply date filters to udaar query
        if ($this->filter === 'daily') {
            $udaarQuery->whereDate('buy_date', $this->selectedDate);
        } elseif ($this->filter === 'monthly') {
            $udaarQuery->whereYear('buy_date', date('Y', strtotime($this->selectedMonth . '-01')))
                       ->whereMonth('buy_date', date('m', strtotime($this->selectedMonth . '-01')));
        }
        
        $udaarCustomers = $udaarQuery
            ->select('customer_name', 'customer_number', DB::raw('MIN(buy_date) as first_purchase'), DB::raw('MAX(buy_date) as last_purchase'), DB::raw('COUNT(*) as total_udaar'), DB::raw('SUM(paid_amount) as total_paid'))
            ->groupBy('customer_name', 'customer_number')
            ->get();

        // Get customer numbers from Customer model for sales customers
        $customerNumbers = Customer::where('type', 'Grocery')
            ->whereIn('name', $salesCustomers->pluck('customer_name'))
            ->pluck('number', 'name')
            ->toArray();

        // Combine and get unique customers
        $allCustomers = collect();
        
        $salesCustomers->each(function($customer) use ($allCustomers, $customerNumbers) {
            $allCustomers->push([
                'name' => $customer->customer_name,
                'number' => $customerNumbers[$customer->customer_name] ?? null,
                'first_purchase' => $customer->first_purchase,
                'last_purchase' => $customer->last_purchase,
                'total_sales' => $customer->total_sales,
                'total_udaar' => 0,
                'total_paid' => $customer->total_paid ?? 0,
            ]);
        });

        $udaarCustomers->each(function($customer) use ($allCustomers) {
            $existing = $allCustomers->firstWhere('name', $customer->customer_name);
            if ($existing) {
                // Update existing customer
                $existing['total_udaar'] = $customer->total_udaar;
                $existing['total_paid'] = ($existing['total_paid'] ?? 0) + ($customer->total_paid ?? 0);
                // Use customer_number from udaar if not already set
                if (!$existing['number'] && $customer->customer_number) {
                    $existing['number'] = $customer->customer_number;
                }
            } else {
                $allCustomers->push([
                    'name' => $customer->customer_name,
                    'number' => $customer->customer_number,
                    'first_purchase' => $customer->first_purchase,
                    'last_purchase' => $customer->last_purchase,
                    'total_sales' => 0,
                    'total_udaar' => $customer->total_udaar,
                    'total_paid' => $customer->total_paid ?? 0,
                ]);
            }
        });

        // Apply search
        if ($this->search) {
            $allCustomers = $allCustomers->filter(function($customer) {
                return stripos($customer['name'], $this->search) !== false || 
                       stripos($customer['number'] ?? '', $this->search) !== false;
            });
        }

        // Sort by name
        $allCustomers = $allCustomers->sortBy('name')->values();

        // Paginate manually
        $perPage = 50;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $items = $allCustomers->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $customers = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $allCustomers->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Calculate totals
        $totalCustomers = $allCustomers->count();
        $totalSales = $allCustomers->sum('total_sales');
        $totalUdhaar = $allCustomers->sum('total_udaar');
        $totalPaid = $allCustomers->sum('total_paid');

        return view('livewire.finance.customers-report', compact('customers', 'totalCustomers', 'totalSales', 'totalUdhaar', 'totalPaid'))
            ->title('Customers Report');
    }
}

