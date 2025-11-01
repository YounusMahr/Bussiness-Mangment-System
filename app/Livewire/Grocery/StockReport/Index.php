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
    public function render()
    {
        $today = now()->startOfDay();
        $yesterday = (clone $today)->copy()->subDay();

        $totalSales = (float) (Sale::sum('total_price') ?? 0);
        $salesToday = (float) (Sale::whereDate('date', $today)->sum('total_price') ?? 0);
        $salesYesterday = (float) (Sale::whereDate('date', $yesterday)->sum('total_price') ?? 0);
        $salesChangePct = $salesYesterday > 0
            ? round((($salesToday - $salesYesterday) / max($salesYesterday, 0.0001)) * 100)
            : ($salesToday > 0 ? 100 : 0);

        $udhaarCount = (int) Udaar::count();
        $udhaarToday = (int) Udaar::whereDate('buy_date', $today)->count();
        $udhaarYesterday = (int) Udaar::whereDate('buy_date', $yesterday)->count();
        $udhaarChange = $udhaarToday - $udhaarYesterday; // show as +N or -N

        $productsQuantity = (int) (Product::sum('quantity') ?? 0);
        $soldToday = (int) (SaleItem::whereDate('created_at', $today)->sum('quantity') ?? 0); // net outgoing today

        $overdueUdhaar = (int) Udaar::whereNotNull('due_date')->where('due_date', '<', now())->count();

        return view('livewire.grocery.stock-report.index', compact(
            'totalSales',
            'salesChangePct',
            'udhaarCount',
            'udhaarChange',
            'productsQuantity',
            'soldToday',
            'overdueUdhaar'
        ));
    }
}
