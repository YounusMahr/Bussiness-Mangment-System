<?php

namespace App\Livewire\Grocery\Sales;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;

class Add extends Component
{
    public $items = [];
    public $customer_name = '';
    public $paid_amount = 0;
    public $payment_method = '';
    public $notes = '';
    public $products = [];
    public $overall_discount = 0;
    public $stockErrors = [];
    public $status = 'paid';

    protected $rules = [
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.total' => 'required|numeric|min:0',
        'items.*.discount' => 'nullable|numeric|min:0',
        'overall_discount' => 'nullable|numeric|min:0',
        'customer_name' => 'nullable|string|max:255',
        'payment_method' => 'nullable|string|max:80',
        'notes' => 'nullable|string',
        'status' => 'required|in:paid,unpaid,pending',
    ];

    public function mount() {
        $this->products = Product::where('is_active', 1)->get();
        $this->addItem();
        $this->payment_method = 'Cash';
    }

    public function addItem() {
        $this->items[] = [
            'product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'discount' => 0, 'total' => 0
        ];
    }
    public function removeItem($i) {
        unset($this->items[$i]);
        $this->items = array_values($this->items);
    }
    public function updatedItems($value, $name) {
        [$index, $field] = explode('.', $name);
        unset($this->stockErrors[$index]);
        if ($field === 'product_id' && $this->items[$index]['product_id']) {
            // use find() instead of ->find, casts to array then object
            $productId = $this->items[$index]['product_id'];
            $product = $this->products->where('id', $productId)->first();
            if ($product) {
                $this->items[$index]['unit_price'] = $product->price;
                $this->items[$index]['quantity'] = 1;
                $this->items[$index]['discount'] = 0;
                $this->items[$index]['total'] = $product->price;
            }
        } elseif ($field === 'quantity' || $field === 'unit_price' || $field === 'discount') {
            $qty = $this->items[$index]['quantity'] ?? 1;
            $price = $this->items[$index]['unit_price'] ?? 0;
            $discount = $this->items[$index]['discount'] ?? 0;
            $raw = $qty * $price - $discount;
            $this->items[$index]['total'] = max($raw, 0);
            if (isset($this->items[$index]['product_id']) && $this->items[$index]['product_id']) {
                $product = $this->products->where('id', $this->items[$index]['product_id'])->first();
                if ($product && $qty > $product->quantity) {
                    $this->stockErrors[$index] = "Only {$product->quantity} in stock!";
                }
            }
        }
    }
    public function getTotalAmountProperty() {
        $sum = collect($this->items)->sum('total');
        return max($sum - ($this->overall_discount ?: 0), 0);
    }
    public function getPaidAmountProperty() {
        return $this->total_amount;
    }
    public function save() {
        $this->validate();
        // check all items for stock availability before saving (using cached list to give fast feedback)
        $errors = new MessageBag();
        foreach ($this->items as $i => $item) {
            $product = $this->products->find($item['product_id']);
            if (!$product || $item['quantity'] > $product->quantity) {
                $qty = $product ? $product->quantity : 0;
                $this->stockErrors[$i] = "Only $qty in stock!";
                $errors->add("items.$i.quantity", "Only $qty in stock for " . ($product->name ?? 'product') . "!");
            }
        }
        if ($errors->count()) {
            $this->setErrorBag($errors);
            return;
        }

        DB::transaction(function () {
            // Create sale
            $sale = Sale::create([
                'customer_name'=>$this->customer_name,
                'total_price'=>$this->total_amount,
                'discount'=>$this->overall_discount,
                'paid_amount'=>$this->paid_amount,
                'payment_method'=>$this->payment_method,
                'notes'=>$this->notes,
                'date'=>now(),
                'status'=>$this->status,
            ]);

            foreach ($this->items as $i => $item) {
                // Lock the product row to avoid race conditions
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (!$product) {
                    throw new \RuntimeException('Product not found.');
                }
                // Re-check stock against latest DB value
                if ($item['quantity'] > $product->quantity) {
                    // Abort with a validation-like error so no negative update occurs
                    throw new \RuntimeException("Insufficient stock for {$product->name}. Available: {$product->quantity}");
                }

                // Create sale item
                SaleItem::create([
                    'sale_id'=>$sale->id,
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['quantity'],
                    'unit_price'=>$item['unit_price'],
                    'discount'=>$item['discount'] ?? 0,
                    'total'=>$item['total'],
                ]);

                // Safe decrement (will not go below zero due to check above)
                $product->decrement('quantity', $item['quantity']);
            }
        });

        session()->flash('message', 'Sale recorded successfully!');
        return $this->redirectRoute('sales', ['locale' => app()->getLocale()]);
    }
    public function resetForm() {
        $this->items = [];
        $this->customer_name = '';
        $this->paid_amount = 0;
        $this->payment_method = '';
        $this->notes = '';
        $this->overall_discount = 0;
        $this->status = 'paid';
        $this->addItem();
    }
    public function render() {
        $this->paid_amount = $this->total_amount;
        return view('livewire.grocery.sales.add');
    }
}
