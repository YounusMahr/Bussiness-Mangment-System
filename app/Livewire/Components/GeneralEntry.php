<?php

namespace App\Livewire\Components;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Udaar;
use App\Models\UdaarTransaction;
use App\Models\Installment;
use App\Models\InstallmentTransaction;
use App\Models\PlotSale;
use App\Models\PlotSaleTransaction;
use App\Models\PlotPurchase;
use App\Models\PlotPurchaseTransaction;
use App\Models\GroceryCashTransaction;
use App\Models\InvestCash;
use App\Models\CashOut;
use Livewire\Component;
use Livewire\WithFileUploads;

class GeneralEntry extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $date;
    public $category = '';
    public $operation = '';
    
    // Customer selection
    public $customer_id = '';
    public $search_customer = '';
    public $show_customer_results = false;
    
    // Quick Add Customer
    public $new_customer_name = '';
    public $new_customer_number = '';
    public $new_customer_email = '';
    public $new_customer_image;
    public $is_creating_customer = false;

    // Selection of existing record for Debit/Credit
    public $record_id = '';
    public $records = [];

    // Common fields
    public $amount = 0;
    public $total_amount = 0;
    public $paid_amount = 0;
    public $interest = 0;
    public $notes = '';
    public $due_date;
    public $time_period = '';
    
    // Category specific fields
    public $product_id = '';
    public $plot_purchase_id = '';
    public $vehicle = '';
    public $model = '';
    public $plot_area = '';
    public $location = '';
    public $plotPurchases = [];

    protected $listeners = ['open-general-entry' => 'open'];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->plotPurchases = PlotPurchase::all();
    }

    public function open()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function resetForm()
    {
        $this->category = '';
        $this->operation = '';
        $this->customer_id = '';
        $this->search_customer = '';
        $this->record_id = '';
        $this->amount = 0;
        $this->total_amount = 0;
        $this->paid_amount = 0;
        $this->interest = 0;
        $this->notes = '';
        $this->due_date = null;
        $this->time_period = '';
        $this->product_id = '';
        $this->vehicle = '';
        $this->model = '';
        $this->plot_area = '';
        $this->location = '';
        $this->is_creating_customer = false;
        $this->new_customer_name = '';
        $this->new_customer_number = '';
        $this->new_customer_email = '';
        $this->new_customer_image = null;
    }

    public function updatedSearchCustomer()
    {
        $this->show_customer_results = !empty($this->search_customer);
    }

    public function selectCustomer($id, $name)
    {
        $this->customer_id = $id;
        $this->search_customer = $name;
        $this->show_customer_results = false;
        $this->loadRecords();
    }

    public function loadRecords()
    {
        if (!$this->customer_id || !$this->category || $this->operation === 'create') {
            $this->records = [];
            return;
        }

        switch ($this->category) {
            case 'udhaar':
                $user = Customer::find($this->customer_id);
                $this->records = Udaar::where('customer_name', $user->name)->get();
                break;
            case 'installments':
                $this->records = Installment::where('customer_id', $this->customer_id)->get();
                break;
            case 'plot_sale':
                $user = Customer::find($this->customer_id);
                $this->records = PlotSale::where('customer_name', $user->name)->get();
                break;
            case 'plot_purchase':
                $this->records = PlotPurchase::where('customer_id', $this->customer_id)->get();
                break;
            case 'credit':
                $this->records = GroceryCashTransaction::where('customer_id', $this->customer_id)
                    ->where('type', 'cash-in')
                    ->get();
                break;
        }
    }

    public function updatedCategory()
    {
        $this->loadRecords();
    }

    public function updatedOperation()
    {
        $this->loadRecords();
    }

    public function createCustomer()
    {
        $this->validate([
            'new_customer_name' => 'required|string|max:255',
            'new_customer_number' => 'required|string|max:255',
            'new_customer_email' => 'nullable|email|max:255',
            'new_customer_image' => 'nullable|image|max:2048',
        ]);

        $type = 'General';
        if ($this->category === 'udhaar' || $this->category === 'credit') $type = 'Grocery';
        if ($this->category === 'installments') $type = 'Car-installment';
        if ($this->category === 'plot_sale' || $this->category === 'plot_purchase') $type = 'Plot';

        $imagePath = null;
        if ($this->new_customer_image) {
            $imagePath = $this->new_customer_image->store('customers', 'public');
        }

        $customer = Customer::create([
            'name' => $this->new_customer_name,
            'number' => $this->new_customer_number,
            'email' => $this->new_customer_email ?: null,
            'image' => $imagePath,
            'type' => $type,
        ]);

        $this->selectCustomer($customer->id, $customer->name);
        $this->is_creating_customer = false;
        $this->new_customer_name = '';
        $this->new_customer_number = '';
        $this->new_customer_email = '';
        $this->new_customer_image = null;
    }

    public function save()
    {
        $this->validate($this->getValidationRules());

        switch ($this->category) {
            case 'udhaar': $this->saveUdhaar(); break;
            case 'credit': $this->saveCredit(); break;
            case 'installments': $this->saveInstallment(); break;
            case 'plot_sale': $this->savePlotSale(); break;
            case 'plot_purchase': $this->savePlotPurchase(); break;
        }

        session()->flash('success', 'Record created successfully!');
        $this->dispatch('record-saved');
        $this->resetForm();
    }

    protected function getValidationRules()
    {
        $rules = [
            'category' => 'required',
            'operation' => 'required',
            'customer_id' => 'required',
            'date' => 'required|date',
        ];

        if ($this->operation === 'create') {
            if ($this->category === 'udhaar') {
                $rules['total_amount'] = 'required|numeric|min:0';
                $rules['paid_amount'] = 'required|numeric|min:0';
            }
            if ($this->category === 'installments') {
                $rules['vehicle'] = 'required';
                $rules['total_amount'] = 'required|numeric|min:0';
            }
            if ($this->category === 'plot_sale') {
                $rules['plot_purchase_id'] = 'required|exists:plot_purchases,id';
                $rules['total_amount'] = 'required|numeric|min:0';
            }
            if ($this->category === 'plot_purchase') {
                $rules['total_amount'] = 'required|numeric|min:0';
            }
        } else {
            $rules['record_id'] = 'required';
            $rules['amount'] = 'required|numeric|min:0.01';
        }

        return $rules;
    }

    protected function saveUdhaar()
    {
        $customer = Customer::find($this->customer_id);
        if ($this->operation === 'create') {
            $remaining = max($this->total_amount + $this->interest - $this->paid_amount, 0);
            $udaar = Udaar::create([
                'buy_date' => $this->date,
                'customer_name' => $customer->name,
                'customer_number' => $customer->number,
                'paid_amount' => $this->paid_amount,
                'remaining_amount' => $remaining,
                'interest_amount' => $this->interest,
                'due_date' => $this->due_date,
                'time_period' => $this->time_period,
                'product_id' => $this->product_id ?: null,
                'notes' => $this->notes,
            ]);

            UdaarTransaction::create([
                'udaar_id' => $udaar->id,
                'date' => $this->date,
                'type' => 'udaar-in',
                'new_udaar_amount' => $this->total_amount,
                'interest_amount' => $this->interest,
                'paid_amount_before' => 0,
                'remaining_amount_before' => $this->total_amount + $this->interest,
                'paid_amount_after' => 0,
                'remaining_amount_after' => $this->total_amount + $this->interest,
                'notes' => $this->notes ?: 'Initial udhaar record created',
            ]);

            if ($this->paid_amount > 0) {
                UdaarTransaction::create([
                    'udaar_id' => $udaar->id,
                    'date' => $this->date,
                    'type' => 'udaar-out',
                    'payment_amount' => $this->paid_amount,
                    'paid_amount_before' => 0,
                    'remaining_amount_before' => $this->total_amount + $this->interest,
                    'paid_amount_after' => $this->paid_amount,
                    'remaining_amount_after' => $remaining,
                    'notes' => 'Initial payment',
                ]);
            }
        } else {
            $udaar = Udaar::find($this->record_id);
            $before_paid = $udaar->paid_amount;
            $before_remaining = $udaar->remaining_amount;

            if ($this->operation === 'credit') { // Payment received
                $udaar->paid_amount += $this->amount;
                $udaar->remaining_amount = max($udaar->remaining_amount - $this->amount, 0);
                $type = 'udaar-out';
            } else { // New debt
                $udaar->remaining_amount += $this->amount;
                $type = 'udaar-in';
            }
            $udaar->save();

            UdaarTransaction::create([
                'udaar_id' => $udaar->id,
                'date' => $this->date,
                'type' => $type,
                'payment_amount' => $this->amount,
                'new_udaar_amount' => $type === 'udaar-in' ? $this->amount : 0,
                'paid_amount_before' => $before_paid,
                'remaining_amount_before' => $before_remaining,
                'paid_amount_after' => $udaar->paid_amount,
                'remaining_amount_after' => $udaar->remaining_amount,
                'notes' => $this->notes,
            ]);
        }
    }

    protected function saveCredit()
    {
        if ($this->operation === 'create') {
            $totalReturn = $this->total_amount + $this->interest;
            
            GroceryCashTransaction::create([
                'date' => $this->date,
                'customer_id' => $this->customer_id,
                'type' => 'cash-in',
                'invest_cash' => $this->total_amount,
                'interest' => $this->interest,
                'time_period' => $this->time_period,
                'due_date' => $this->due_date,
                'return_amount' => $totalReturn,
                'available_balance' => $totalReturn,
                'remaining_balance' => $totalReturn,
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            if ($this->paid_amount > 0) {
                GroceryCashTransaction::create([
                    'date' => $this->date,
                    'customer_id' => $this->customer_id,
                    'type' => 'cash-out',
                    'returned_amount' => $this->paid_amount,
                    'available_balance' => 0,
                    'remaining_balance' => max($totalReturn - $this->paid_amount, 0),
                    'status' => 'pending',
                    'notes' => 'Initial return payment',
                ]);
            }
        } else {
            if ($this->operation === 'debit') { // Returning money
                $lastCash = GroceryCashTransaction::where('customer_id', $this->customer_id)
                    ->orderBy('id', 'desc')->first();
                $lastRemaining = $lastCash ? $lastCash->remaining_balance : 0;

                GroceryCashTransaction::create([
                    'date' => $this->date,
                    'customer_id' => $this->customer_id,
                    'type' => 'cash-out',
                    'returned_amount' => $this->amount,
                    'available_balance' => 0,
                    'remaining_balance' => max($lastRemaining - $this->amount, 0),
                    'status' => 'pending',
                    'notes' => $this->notes,
                ]);
            }
        }
    }

    protected function saveInstallment()
    {
        if ($this->operation === 'create') {
            $remaining = max($this->total_amount + $this->interest - $this->paid_amount, 0);
            $installment = Installment::create([
                'date' => $this->date,
                'customer_id' => $this->customer_id,
                'vehicle' => $this->vehicle,
                'model' => $this->model,
                'car_price' => $this->total_amount,
                'paid' => $this->paid_amount,
                'remaining' => $remaining,
                'interest' => $this->interest,
                'total_price' => $this->total_amount + $this->interest,
                'due_date' => $this->due_date,
                'time_period' => $this->time_period,
                'note' => $this->notes,
            ]);

            InstallmentTransaction::create([
                'installment_id' => $installment->id,
                'date' => $this->date,
                'type' => 'add',
                'new_car_price' => $this->total_amount,
                'new_interest' => $this->interest,
                'new_total_price' => $this->total_amount + $this->interest,
                'new_paid' => $this->paid_amount,
                'car_price_before' => 0,
                'paid_before' => 0,
                'interest_before' => 0,
                'remaining_before' => 0,
                'total_price_before' => 0,
                'car_price_after' => $this->total_amount,
                'paid_after' => $this->paid_amount,
                'interest_after' => $this->interest,
                'remaining_after' => $remaining,
                'total_price_after' => $this->total_amount + $this->interest,
                'notes' => $this->notes ?: 'Initial installment recorded',
            ]);
        } else {
            $inst = Installment::find($this->record_id);
            $before_remaining = $inst->remaining;
            $before_paid = $inst->paid;
            $before_car_price = $inst->car_price;
            $before_interest = $inst->interest;
            $before_total = $inst->total_price;
            
            if ($this->operation === 'credit') { // Payment received
                $inst->paid += $this->amount;
                $inst->remaining = max($inst->remaining - $this->amount, 0);
                $type = 'add';
            } else { // Adding price? (debit = adding debt)
                $inst->car_price += $this->amount;
                $inst->total_price += $this->amount;
                $inst->remaining += $this->amount;
                $type = 'add';
            }
            $inst->save();

            InstallmentTransaction::create([
                'installment_id' => $inst->id,
                'date' => $this->date,
                'type' => $type,
                'new_car_price' => $this->operation === 'debit' ? $this->amount : 0,
                'new_paid' => $this->operation === 'credit' ? $this->amount : 0,
                'car_price_before' => $before_car_price,
                'paid_before' => $before_paid,
                'interest_before' => $before_interest,
                'total_price_before' => $before_total,
                'remaining_before' => $before_remaining,
                'car_price_after' => $inst->car_price,
                'paid_after' => $inst->paid,
                'interest_after' => $inst->interest,
                'total_price_after' => $inst->total_price,
                'remaining_after' => $inst->remaining,
                'notes' => $this->notes,
            ]);
        }
    }

    protected function savePlotSale()
    {
        $customer = Customer::find($this->customer_id);
        if ($this->operation === 'create') {
            $remaining = max($this->total_amount + $this->interest - $this->paid_amount, 0);
            $sale = PlotSale::create([
                'date' => $this->date,
                'plot_purchase_id' => $this->plot_purchase_id,
                'customer_name' => $customer->name,
                'customer_number' => $customer->number,
                'total_sale_price' => $this->total_amount,
                'paid' => $this->paid_amount,
                'remaining' => $remaining,
                'interest' => $this->interest,
                'status' => $remaining <= 0 ? 'paid' : 'remaining',
            ]);

            if ($this->paid_amount > 0) {
                PlotSaleTransaction::create([
                    'plot_sale_id' => $sale->id,
                    'date' => $this->date,
                    'type' => 'sale-in',
                    'installment_amount' => $this->paid_amount,
                    'paid_amount' => $this->paid_amount,
                    'payment_amount' => $this->paid_amount,
                    'total_sale_price_before' => $this->total_amount,
                    'paid_before' => 0,
                    'remaining_before' => $this->total_amount,
                    'total_sale_price_after' => $this->total_amount,
                    'paid_after' => $this->paid_amount,
                    'remaining_after' => $remaining,
                    'notes' => $this->notes ?: 'Initial plot sale recorded',
                ]);
            }
        } else {
            $sale = PlotSale::find($this->record_id);
            $before_paid = (float)($sale->paid ?? 0);
            $before_remaining = (float)($sale->remaining ?? 0);

            if ($this->operation === 'credit') {
                $newPaid = $before_paid + $this->amount;
                $newRemaining = max($before_remaining - $this->amount, 0);

                $sale->update([
                    'paid' => $newPaid,
                    'remaining' => $newRemaining,
                    'status' => $newRemaining <= 0 ? 'paid' : 'remaining',
                ]);

                PlotSaleTransaction::create([
                    'plot_sale_id' => $sale->id,
                    'date' => $this->date,
                    'type' => 'sale-in',
                    'installment_amount' => $this->amount,
                    'paid_amount' => $this->amount,
                    'payment_amount' => $this->amount,
                    'total_sale_price_before' => (float)($sale->total_sale_price ?? 0),
                    'paid_before' => $before_paid,
                    'remaining_before' => $before_remaining,
                    'total_sale_price_after' => (float)($sale->total_sale_price ?? 0),
                    'paid_after' => $newPaid,
                    'remaining_after' => $newRemaining,
                    'notes' => $this->notes,
                ]);
            }
        }
    }

    protected function savePlotPurchase()
    {
        if ($this->operation === 'create') {
            $purchase = PlotPurchase::create([
                'customer_id' => $this->customer_id,
                'date' => $this->date,
                'plot_area' => $this->plot_area,
                'plot_price' => $this->total_amount,
                'location' => $this->location,
                'notes' => $this->notes ?: null,
            ]);

            // Record initial paid amount as a purchase-out transaction so it shows on card & history
            if ($this->paid_amount > 0) {
                PlotPurchaseTransaction::create([
                    'plot_purchase_id' => $purchase->id,
                    'date' => $this->date,
                    'type' => 'purchase-out',
                    'payment_amount' => $this->paid_amount,
                    'plot_price_before' => $this->total_amount,
                    'plot_price_after' => max($this->total_amount - $this->paid_amount, 0),
                    'notes' => $this->notes ?: 'Initial payment on purchase',
                ]);
            }
        } else {
            // Purchases usually handle payments out
            $purchase = PlotPurchase::find($this->record_id);
            if ($this->operation === 'debit') {
                 PlotPurchaseTransaction::create([
                    'plot_purchase_id' => $purchase->id,
                    'date' => $this->date,
                    'type' => 'purchase-out',
                    'payment_amount' => $this->amount,
                    'notes' => $this->notes,
                ]);
            }
        }
    }

    public function render()
    {
        $customers = [];
        if ($this->show_customer_results) {
            $typeToFilter = match($this->category) {
                'udhaar', 'credit' => 'Grocery',
                'installments' => 'Car-installment',
                'plot_sale', 'plot_purchase' => 'Plot',
                default => null
            };

            $query = Customer::query();
            
            if ($typeToFilter) {
                $query->where('type', $typeToFilter);
            }

            $customers = $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search_customer . '%')
                  ->orWhere('number', 'like', '%' . $this->search_customer . '%');
            })
            ->limit(5)
            ->get();
        }

        $products = Product::orderBy('name')->get();

        return view('livewire.components.general-entry', [
            'customer_results' => $customers,
            'products' => $products,
        ]);
    }
}
