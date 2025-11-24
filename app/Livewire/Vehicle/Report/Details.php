<?php

namespace App\Livewire\Vehicle\Report;

use App\Models\Installment;
use Livewire\Component;
use Livewire\WithPagination;

class Details extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

    public $filter = 'all'; // all, daily, monthly
    public $selectedDate;
    public $selectedMonth;

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

    public function printReport()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        $query = Installment::with('customer')
            ->orderBy('date', 'desc');

        // Apply filters
        if ($this->filter === 'daily') {
            $query->whereDate('date', $this->selectedDate);
        } elseif ($this->filter === 'monthly') {
            $query->whereYear('date', date('Y', strtotime($this->selectedMonth . '-01')))
                  ->whereMonth('date', date('m', strtotime($this->selectedMonth . '-01')));
        }

        $installments = $query->paginate(50);

        // Calculate totals
        $totalSales = $query->sum('total_price');
        $totalPaid = $query->sum('paid');
        $totalRemaining = $query->sum('remaining');
        $totalInterest = $query->sum('interest');
        $totalCarPrice = $query->sum('car_price');

        return view('livewire.vehicle.report.details', compact('installments', 'totalSales', 'totalPaid', 'totalRemaining', 'totalInterest', 'totalCarPrice'))
            ->title('Vehicle Installment Report');
    }
}
