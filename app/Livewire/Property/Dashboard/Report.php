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
        $this->totalSales = (float)(PlotSale::sum('total_sale_price') ?? 0);

        // Sales Plots: Count of plot sales
        $this->salesPlots = PlotSale::count();

        // Total plots purchased
        $totalPlotsPurchased = PlotPurchase::count();

        // Remaining Plots: Total purchased - sold
        $this->remainingPlots = max($totalPlotsPurchased - $this->salesPlots, 0);

        // Total Profit: Total sales - Purchase cost of SOLD plots only
        // Get the purchase cost only for plots that were actually sold
        $totalPurchaseCostOfSoldPlots = 0;
        
        $sales = PlotSale::with('plotPurchase')->get();
        foreach ($sales as $sale) {
            if ($sale->plotPurchase && $sale->plotPurchase->plot_price) {
                $totalPurchaseCostOfSoldPlots += (float)$sale->plotPurchase->plot_price;
            }
        }
        
        $this->totalProfit = $this->totalSales - $totalPurchaseCostOfSoldPlots;
    }

    public function render()
    {
        return view('livewire.property.dashboard.report');
    }
}
