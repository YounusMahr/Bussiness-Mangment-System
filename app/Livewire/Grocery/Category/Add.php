<?php

namespace App\Livewire\Grocery\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Add extends Component
{
    public $name = '';
    public $slug = '';
    public $description = '';
    public $is_active = true;
    public $parent_id = null;
    public $position = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:categories,slug',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'parent_id' => 'nullable|exists:categories,id',
        'position' => 'nullable|integer|min:0',
    ];

    public bool $slugManuallySet = false;

    public function updatedName()
    {
        if (!$this->slugManuallySet) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function updatedSlug()
    {
        $this->slugManuallySet = true;
        $this->slug = Str::slug($this->slug);
    }

    public function save()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'parent_id' => $this->parent_id,
            'position' => $this->position ?? 0,
        ]);

        session()->flash('message', 'Category created successfully!');

        return $this->redirectRoute('categories');
    }

    public function render()
    {
        return view('livewire.grocery.category.add')
            ->title('Add Category');
    }
}
