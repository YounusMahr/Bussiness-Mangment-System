<?php

namespace App\Livewire\Grocery\StockReport;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Udaar;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Carbon;

class Index extends Component
{
    protected $layout = 'layouts.app';

    public $totalSales = 0;
    public $salesChangePct = 0;
    public $udhaarCount = 0;
    public $udhaarChange = 0;
    public $productsQuantity = 0;
    public $soldToday = 0;
    public $overdueUdhaar = 0;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        $today = now()->startOfDay();
        $yesterday = (clone $today)->copy()->subDay();

        $this->totalSales = (float) (Sale::sum('total_price') ?? 0);
        $salesToday = (float) (Sale::whereDate('date', $today)->sum('total_price') ?? 0);
        $salesYesterday = (float) (Sale::whereDate('date', $yesterday)->sum('total_price') ?? 0);
        $this->salesChangePct = $salesYesterday > 0
            ? round((($salesToday - $salesYesterday) / max($salesYesterday, 0.0001)) * 100)
            : ($salesToday > 0 ? 100 : 0);

        $this->udhaarCount = (int) Udaar::count();
        $udhaarToday = (int) Udaar::whereDate('buy_date', $today)->count();
        $udhaarYesterday = (int) Udaar::whereDate('buy_date', $yesterday)->count();
        $this->udhaarChange = $udhaarToday - $udhaarYesterday; // show as +N or -N

        $this->productsQuantity = (int) (Product::sum('quantity') ?? 0);
        $this->soldToday = (int) (SaleItem::whereDate('created_at', $today)->sum('quantity') ?? 0); // net outgoing today

        $this->overdueUdhaar = (int) Udaar::whereNotNull('due_date')->where('due_date', '<', now())->count();
    }

    public function render()
    {
        return view('livewire.grocery.stock-report.index');
    }
}

