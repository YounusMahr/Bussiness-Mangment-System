<?php

namespace App\Livewire\Grocery;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddProduct extends Component
{
    use WithFileUploads;

    protected $layout = 'layouts.app';

    public $name = '';
    public $sku = '';
    public $description = '';
    public $quantity = 0;
    public $price = 0;
    public $cost = 0;
    public $is_active = true;
    public $category_id = null;
    public $image = null;

    protected $rules = [
        'category_id' => 'nullable|exists:categories,id',
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku',
        'description' => 'nullable|string',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'is_active' => 'boolean',
        'image' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();
        $imagePath = null;
        if ($this->image && $this->image instanceof \Livewire\TemporaryUploadedFile) {
            $imagePath = $this->image->store('products', 'public');
        }

        Product::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'image' => $imagePath,
        ]);

        session()->flash('message', 'Product created successfully!');
        return $this->redirectRoute('products');
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.grocery.add-product', compact('categories'))
            ->title('Add Product');
    }
}
