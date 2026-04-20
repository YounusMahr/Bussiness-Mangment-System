<?php

namespace App\Livewire\History;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFilter = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $khata = DB::table('udaar_transactions')
            ->join('udaars', 'udaars.id', '=', 'udaar_transactions.udaar_id')
            ->select(
                'udaar_transactions.date',
                DB::raw("'Khata' as module"),
                'udaar_transactions.type',
                'udaars.customer_name as entity',
                DB::raw('COALESCE(udaar_transactions.payment_amount, 0) + COALESCE(udaar_transactions.new_udaar_amount, 0) + COALESCE(udaar_transactions.interest_amount, 0) as amount'),
                'udaar_transactions.notes',
                'udaar_transactions.created_at'
            );

        $credits = DB::table('grocery_cash_transactions')
            ->join('customers', 'customers.id', '=', 'grocery_cash_transactions.customer_id')
            ->select(
                'grocery_cash_transactions.date',
                DB::raw("'Credit' as module"),
                'grocery_cash_transactions.type',
                'customers.name as entity',
                DB::raw('COALESCE(grocery_cash_transactions.invest_cash, 0) + COALESCE(grocery_cash_transactions.interest, 0) + COALESCE(grocery_cash_transactions.returned_amount, 0) as amount'),
                'grocery_cash_transactions.notes',
                'grocery_cash_transactions.created_at'
            );

        $installments = DB::table('installment_transactions')
            ->join('installments', 'installments.id', '=', 'installment_transactions.installment_id')
            ->join('customers', 'customers.id', '=', 'installments.customer_id')
            ->select(
                'installment_transactions.date',
                DB::raw("'Car Installment' as module"),
                'installment_transactions.type',
                'customers.name as entity',
                DB::raw('COALESCE(installment_transactions.new_car_price, 0) + COALESCE(installment_transactions.new_interest, 0) + COALESCE(installment_transactions.new_paid, 0) + COALESCE(installment_transactions.return_payment, 0) as amount'),
                'installment_transactions.notes',
                'installment_transactions.created_at'
            );

        $plotSales = DB::table('plot_sale_transactions')
            ->join('plot_sales', 'plot_sales.id', '=', 'plot_sale_transactions.plot_sale_id')
            ->select(
                'plot_sale_transactions.date',
                DB::raw("'Plot Sale' as module"),
                'plot_sale_transactions.type',
                'plot_sales.customer_name as entity',
                DB::raw('COALESCE(plot_sale_transactions.payment_amount, 0) as amount'),
                'plot_sale_transactions.notes',
                'plot_sale_transactions.created_at'
            );

        $plotPurchases = DB::table('plot_purchase_transactions')
            ->join('plot_purchases', 'plot_purchases.id', '=', 'plot_purchase_transactions.plot_purchase_id')
            ->join('customers', 'customers.id', '=', 'plot_purchases.customer_id')
            ->select(
                'plot_purchase_transactions.date',
                DB::raw("'Plot Purchase' as module"),
                'plot_purchase_transactions.type',
                'customers.name as entity',
                DB::raw('COALESCE(plot_purchase_transactions.payment_amount, 0) as amount'),
                'plot_purchase_transactions.notes',
                'plot_purchase_transactions.created_at'
            );

        $unionQuery = $khata
            ->unionAll($credits)
            ->unionAll($installments)
            ->unionAll($plotSales)
            ->unionAll($plotPurchases);

        $transactions = DB::table(DB::raw("({$unionQuery->toSql()}) as transactions"))
            ->mergeBindings($unionQuery)
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $sub->where('entity', 'like', '%'.$this->search.'%')
                        ->orWhere('notes', 'like', '%'.$this->search.'%')
                        ->orWhere('module', 'like', '%'.$this->search.'%')
                        ->orWhere('type', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->dateFilter !== 'all', function($q) {
                if ($this->dateFilter === 'daily') {
                    $q->whereDate('date', today());
                } elseif ($this->dateFilter === 'monthly') {
                    $q->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                } elseif ($this->dateFilter === 'yearly') {
                    $q->whereYear('date', now()->year);
                }
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.history.index', [
            'transactions' => $transactions
        ]);
    }
}
