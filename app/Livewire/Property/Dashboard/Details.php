<?php

namespace App\Livewire\Property\Dashboard;

use App\Models\PlotSale;
use Livewire\Component;
use Livewire\WithPagination;

class Details extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

    public $filter = 'all'; // all, daily, monthly
    public $selectedDate;
    public $selectedMonth;
    public $selectedYear;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedMonth = now()->format('Y-m');
        $this->selectedYear = now()->format('Y');
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

    public function updatedSelectedYear()
    {
        $this->resetPage();
    }

    public function printReport()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        $query = PlotSale::with('plotPurchase')
            ->orderBy('date', 'desc');

        // Apply filters
        if ($this->filter === 'daily') {
            $query->whereDate('date', $this->selectedDate);
        } elseif ($this->filter === 'monthly') {
            $query->whereYear('date', date('Y', strtotime($this->selectedMonth . '-01')))
                  ->whereMonth('date', date('m', strtotime($this->selectedMonth . '-01')));
        }

        $sales = $query->paginate(50);

        // Calculate totals
        $totalSales = $query->sum('total_sale_price');
        $totalPaid = $query->sum('paid');
        $totalRemaining = $query->sum('remaining');
        $totalInterest = $query->sum('interest');

        return view('livewire.property.dashboard.details', compact('sales', 'totalSales', 'totalPaid', 'totalRemaining', 'totalInterest'))
            ->title('Property Sales Report');
    }
}
