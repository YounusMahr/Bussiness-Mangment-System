<?php

namespace App\Livewire\Finance;

use App\Models\Sale;
use App\Models\Installment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class RevenueReport extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

    public $filter = 'all'; // all, daily, monthly
    public $selectedDate;
    public $selectedMonth;
    public $systemFilter = 'all'; // all, grocery, car-installment

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

    public function updatedSystemFilter()
    {
        $this->resetPage();
    }

    public function printReport()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        $grocerySales = collect();
        $carInstallments = collect();
        
        // Grocery Sales
        if ($this->systemFilter === 'all' || $this->systemFilter === 'grocery') {
            $groceryQuery = Sale::orderBy('date', 'desc');
            
            if ($this->filter === 'daily') {
                $groceryQuery->whereDate('date', $this->selectedDate);
            } elseif ($this->filter === 'monthly') {
                $groceryQuery->whereYear('date', date('Y', strtotime($this->selectedMonth . '-01')))
                      ->whereMonth('date', date('m', strtotime($this->selectedMonth . '-01')));
            }
            
            $grocerySales = $groceryQuery->get()->map(function($sale) {
                return (object)[
                    'id' => 'grocery-' . $sale->id,
                    'date' => $sale->date,
                    'system' => 'Grocery',
                    'customer_name' => $sale->customer_name,
                    'customer_number' => null,
                    'total_price' => $sale->total_price,
                    'paid_amount' => $sale->paid_amount,
                    'remaining_amount' => $sale->total_price - $sale->paid_amount,
                    'payment_method' => $sale->payment_method,
                ];
            });
        }
        
        // Car Installments
        if ($this->systemFilter === 'all' || $this->systemFilter === 'car-installment') {
            $installmentQuery = Installment::with('customer')->orderBy('date', 'desc');
            
            if ($this->filter === 'daily') {
                $installmentQuery->whereDate('date', $this->selectedDate);
            } elseif ($this->filter === 'monthly') {
                $installmentQuery->whereYear('date', date('Y', strtotime($this->selectedMonth . '-01')))
                      ->whereMonth('date', date('m', strtotime($this->selectedMonth . '-01')));
            }
            
            $carInstallments = $installmentQuery->get()->map(function($installment) {
                return (object)[
                    'id' => 'installment-' . $installment->id,
                    'date' => $installment->date,
                    'system' => 'Car-Installment',
                    'customer_name' => $installment->customer->name ?? 'N/A',
                    'customer_number' => $installment->number ?? $installment->customer->number ?? null,
                    'total_price' => $installment->total_price,
                    'paid_amount' => $installment->paid,
                    'remaining_amount' => $installment->remaining,
                    'payment_method' => 'Installment',
                ];
            });
        }
        
        // Combine and sort
        $allRecords = $grocerySales->merge($carInstallments)->sortByDesc('date')->values();
        
        // Paginate manually
        $perPage = 50;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $items = $allRecords->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $sales = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $allRecords->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Calculate totals
        $totalRevenue = $allRecords->sum('paid_amount');
        $totalSales = $allRecords->count();
        $totalPaid = $allRecords->sum('paid_amount');
        $totalRemaining = $allRecords->sum('remaining_amount');

        return view('livewire.finance.revenue-report', compact('sales', 'totalRevenue', 'totalSales', 'totalPaid', 'totalRemaining'))
            ->title('Revenue Report');
    }
}
