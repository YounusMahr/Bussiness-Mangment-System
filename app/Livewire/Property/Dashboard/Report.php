<?php

namespace App\Livewire\Property\Dashboard;

use App\Models\PlotPurchase;
use App\Models\PlotSale;
use Livewire\Component;

class Report extends Component
{
    public $totalProfit = 0;
    public $totalSales = 0;
    public $totalSalesAmountReceived = 0;
    public $totalSalesRemaining = 0;
    public $salesPlots = 0;
    public $remainingPlots = 0;
    public $totalPlotsPurchased = 0;
    public $totalPurchaseCost = 0;
    public $totalPurchaseCostOfSoldPlots = 0;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Total plots purchased
        $this->totalPlotsPurchased = PlotPurchase::count();

        // Total Sales: Sum of all plot sales total_sale_price
        $this->totalSales = (float)(PlotSale::sum('total_sale_price') ?? 0);

        // Total amount received (paid) from sales
        $this->totalSalesAmountReceived = (float)(PlotSale::sum('paid') ?? 0);

        // Total remaining (outstanding) from sales
        $this->totalSalesRemaining = (float)(PlotSale::sum('remaining') ?? 0);

        // Sales Plots: Count of plot sales
        $this->salesPlots = PlotSale::count();

        // Remaining Plots: Total purchased - sold
        $this->remainingPlots = max($this->totalPlotsPurchased - $this->salesPlots, 0);

        // Total Purchase Cost: Sum of plot_price for ALL purchased plots
        $this->totalPurchaseCost = (float)(PlotPurchase::sum('plot_price') ?? 0);

        // Total Profit: From plot sales = (Sale Price - Purchase Cost) for each sold plot
        // Purchase cost comes from plot_purchases.plot_price linked via plot_purchase_id
        $totalProfitSum = 0;
        $totalCostOfSold = 0;

        $plotSales = PlotSale::with('plotPurchase')->get();
        foreach ($plotSales as $sale) {
            $salePrice = (float)($sale->total_sale_price ?? 0);
            $purchaseCost = 0;
            if ($sale->plotPurchase) {
                $purchaseCost = (float)($sale->plotPurchase->plot_price ?? 0);
            }
            $totalCostOfSold += $purchaseCost;
            $totalProfitSum += ($salePrice - $purchaseCost);
        }

        $this->totalProfit = round($totalProfitSum, 2);
        $this->totalPurchaseCostOfSoldPlots = $totalCostOfSold;
    }

    public function render()
    {
        return view('livewire.property.dashboard.report');
    }
}
