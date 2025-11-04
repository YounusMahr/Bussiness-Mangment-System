<?php

namespace App\Livewire\Grocery\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public Category $category;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $is_active = true;
    public $parent_id = null;
    public $position = 0;

    public bool $slugManuallySet = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $this->category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
            'position' => 'nullable|integer|min:0',
        ];
    }

    public function mount(Category $category): void
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->is_active = (bool) $category->is_active;
        $this->parent_id = $category->parent_id;
        $this->position = $category->position;
    }

    public function updatedName(): void
    {
        if (!$this->slugManuallySet) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function updatedSlug(): void
    {
        $this->slugManuallySet = true;
        $this->slug = Str::slug($this->slug);
    }

    public function save(): void
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'parent_id' => $this->parent_id,
            'position' => $this->position ?? 0,
        ]);

        session()->flash('message', 'Category updated successfully!');

        $this->redirectRoute('categories', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.category.edit')
            ->title('Edit Category');
    }
}


