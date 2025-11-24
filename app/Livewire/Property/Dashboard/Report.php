<?php

namespace App\Livewire\Property\Dashboard;

use App\Models\PlotPurchase;
use App\Models\PlotSale;
use Livewire\Component;

class Report extends Component
{
    public $totalProfit = 0;
    public $totalSales = 0;
    public $salesPlots = 0;
    public $remainingPlots = 0;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Total Sales: Sum of all plot sales total_sale_price
        $this->totalSales = PlotSale::sum('total_sale_price') ?? 0;

        // Sales Plots: Count of plot sales
        $this->salesPlots = PlotSale::count();

        // Total plots purchased
        $totalPlotsPurchased = PlotPurchase::count();

        // Remaining Plots: Total purchased - sold
        $this->remainingPlots = $totalPlotsPurchased - $this->salesPlots;

        // Total Profit: Total sales - Total purchase cost
        $totalPurchaseCost = PlotPurchase::sum('plot_price') ?? 0;
        $this->totalProfit = $this->totalSales - $totalPurchaseCost;
    }

    public function render()
    {
        return view('livewire.property.dashboard.report');
    }
}
