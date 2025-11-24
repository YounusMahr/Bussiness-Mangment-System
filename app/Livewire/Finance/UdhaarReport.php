<?php

namespace App\Livewire\Finance;

use App\Models\Udaar;
use Livewire\Component;
use Livewire\WithPagination;

class UdhaarReport extends Component
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
        $query = Udaar::orderBy('buy_date', 'desc');

        // Apply filters
        if ($this->filter === 'daily') {
            $query->whereDate('buy_date', $this->selectedDate);
        } elseif ($this->filter === 'monthly') {
            $query->whereYear('buy_date', date('Y', strtotime($this->selectedMonth . '-01')))
                  ->whereMonth('buy_date', date('m', strtotime($this->selectedMonth . '-01')));
        }

        $udaars = $query->paginate(50);

        // Calculate totals
        $totalUdhaar = $query->sum('remaining_amount');
        $totalPaid = $query->sum('paid_amount');
        $totalRemaining = $query->sum('remaining_amount');
        $totalInterest = $query->sum('interest_amount');

        return view('livewire.finance.udhaar-report', compact('udaars', 'totalUdhaar', 'totalPaid', 'totalRemaining', 'totalInterest'))
            ->title('Udhaar Report');
    }
}
