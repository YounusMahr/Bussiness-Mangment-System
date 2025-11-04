<?php

namespace App\Livewire\Grocery;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use WithFileUploads;
    public Product $product;

    public $name = '';
    public $sku = '';
    public $description = '';
    public $quantity = 0;
    public $price = 0;
    public $is_active = true;
    public $category_id = null;
    public $image;

    protected function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $this->product->id,
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->quantity = $product->quantity;
        $this->price = $product->price;
        $this->is_active = $product->is_active;
        $this->category_id = $product->category_id;
        $this->image = $product->image;
    }

    public function save(): void
    {
        $this->validate();
        $imagePath = $this->product->image;
        if ($this->image && $this->image instanceof \Livewire\TemporaryUploadedFile) {
            $imagePath = $this->image->store('products', 'public');
        }
        $this->product->update([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'is_active' => $this->is_active,
            'image' => $imagePath,
        ]);
        session()->flash('message', 'Product updated successfully!');
        $this->redirectRoute('products', ['locale' => app()->getLocale()]);
    }
    public function removeImage()
    {
        $this->image = null;
    }

    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.grocery.edit-product', compact('categories'));
    }
}


