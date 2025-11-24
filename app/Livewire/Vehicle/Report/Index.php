<?php

namespace App\Livewire\Vehicle\Report;

use App\Models\Installment;
use Livewire\Component;

class Index extends Component
{
    protected $layout = 'layouts.app';

    public $totalProfit = 0;
    public $totalSales = 0;
    public $totalInstallments = 0;
    public $totalRemaining = 0;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Total Sales: Sum of all installment total_price
        $this->totalSales = Installment::sum('total_price') ?? 0;

        // Total Installments: Count of installments
        $this->totalInstallments = Installment::count();

        // Total Remaining: Sum of all remaining amounts
        $this->totalRemaining = Installment::sum('remaining') ?? 0;

        // Total Profit: Total sales - Total remaining (simplified calculation)
        // In reality, profit would be sales - cost, but we don't have cost data
        // So we'll show total sales as profit indicator
        $this->totalProfit = $this->totalSales - $this->totalRemaining;
    }

    public function render()
    {
        return view('livewire.vehicle.report.index')
            ->title('Vehicle Dashboard');
    }
}
