<?php

namespace App\Livewire\Finance;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ProductsReport extends Component
{
    use WithPagination;
    protected $layout = 'layouts.app';

    public $filter = 'all'; // all, daily, monthly
    public $selectedDate;
    public $selectedMonth;
    public $search = '';

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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function printReport()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        $query = Product::with('category')
            ->orderBy('name', 'asc');

        // Apply search
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Apply filters
        if ($this->filter === 'daily') {
            $query->whereDate('created_at', $this->selectedDate);
        } elseif ($this->filter === 'monthly') {
            $query->whereYear('created_at', date('Y', strtotime($this->selectedMonth . '-01')))
                  ->whereMonth('created_at', date('m', strtotime($this->selectedMonth . '-01')));
        }

        $products = $query->paginate(50);

        // Calculate totals
        $totalProducts = $query->count();
        $totalStock = $query->sum('quantity');
        $totalValue = $query->sum(DB::raw('quantity * price'));

        return view('livewire.finance.products-report', compact('products', 'totalProducts', 'totalStock', 'totalValue'))
            ->title('Products Report');
    }
}

