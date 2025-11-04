<?php

namespace App\Livewire\Grocery\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class Edit extends Component
{
    public $saleId;
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

    public function mount(Sale $sale)
    {
        $this->saleId = $sale->id;
        $this->customer_name = $sale->customer_name;
        $this->overall_discount = $sale->discount ?? 0;
        $this->paid_amount = $sale->paid_amount ?? 0;
        $this->payment_method = $sale->payment_method ?: 'Cash';
        $this->notes = $sale->notes;
        $this->status = $sale->status ?: 'paid';

        $this->products = Product::where('is_active', 1)->get();

        $this->items = $sale->saleItems->map(function (SaleItem $si) {
            return [
                'product_id' => $si->product_id,
                'quantity' => $si->quantity,
                'unit_price' => (float) $si->unit_price,
                'discount' => (float) ($si->discount ?? 0),
                'total' => (float) $si->total,
            ];
        })->values()->toArray();

        if (empty($this->items)) {
            $this->items[] = ['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'discount' => 0, 'total' => 0];
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'discount' => 0, 'total' => 0
        ];
    }

    public function removeItem($i)
    {
        unset($this->items[$i]);
        $this->items = array_values($this->items);
    }

    public function updatedItems($value, $name)
    {
        [$index, $field] = explode('.', $name);
        unset($this->stockErrors[$index]);
        if ($field === 'product_id' && $this->items[$index]['product_id']) {
            $productId = $this->items[$index]['product_id'];
            $product = collect($this->products)->where('id', $productId)->first();
            if ($product) {
                $this->items[$index]['unit_price'] = $product->price;
                $this->items[$index]['quantity'] = 1;
                $this->items[$index]['discount'] = 0;
                $this->items[$index]['total'] = $product->price;
            }
        } elseif (in_array($field, ['quantity', 'unit_price', 'discount'], true)) {
            $qty = $this->items[$index]['quantity'] ?? 1;
            $price = $this->items[$index]['unit_price'] ?? 0;
            $discount = $this->items[$index]['discount'] ?? 0;
            $raw = $qty * $price - $discount;
            $this->items[$index]['total'] = max($raw, 0);
            if (!empty($this->items[$index]['product_id'])) {
                $product = collect($this->products)->where('id', $this->items[$index]['product_id'])->first();
                if ($product && $qty > $product->quantity) {
                    $this->stockErrors[$index] = "Only {$product->quantity} in stock!";
                }
            }
        }
    }

    public function getTotalAmountProperty()
    {
        $sum = collect($this->items)->sum('total');
        return max($sum - ($this->overall_discount ?: 0), 0);
    }

    public function update()
    {
        $this->validate();

        // optimistic pre-check against cached list for quick UX
        $errors = new MessageBag();
        foreach ($this->items as $i => $item) {
            $product = collect($this->products)->firstWhere('id', $item['product_id']);
            if (!$product) {
                $errors->add("items.$i.product_id", 'Product not found.');
                continue;
            }
            if ($item['quantity'] < 1) {
                $errors->add("items.$i.quantity", 'Quantity must be at least 1.');
            }
        }
        if ($errors->count()) {
            $this->setErrorBag($errors);
            return;
        }

        DB::transaction(function () {
            $sale = Sale::with('saleItems')->lockForUpdate()->findOrFail($this->saleId);

            // Restore stock from existing sale items
            foreach ($sale->saleItems as $existing) {
                $product = Product::lockForUpdate()->find($existing->product_id);
                if ($product) {
                    $product->increment('quantity', $existing->quantity);
                }
            }

            // Clear existing sale items
            $sale->saleItems()->delete();

            // Re-validate and apply new items with stock checks
            foreach ($this->items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (!$product) {
                    throw new \RuntimeException('Product not found.');
                }
                if ($item['quantity'] > $product->quantity) {
                    throw new \RuntimeException("Insufficient stock for {$product->name}. Available: {$product->quantity}");
                }

                $sale->saleItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total'],
                ]);

                $product->decrement('quantity', $item['quantity']);
            }

            // Update sale header
            $sale->update([
                'customer_name' => $this->customer_name,
                'total_price' => $this->total_amount,
                'discount' => $this->overall_discount,
                'paid_amount' => $this->paid_amount ?: $this->total_amount,
                'payment_method' => $this->payment_method ?: 'Cash',
                'notes' => $this->notes,
                'status' => $this->status,
            ]);
        });

        session()->flash('message', 'Sale updated successfully!');
        return $this->redirectRoute('sales', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.sales.edit');
    }
}
