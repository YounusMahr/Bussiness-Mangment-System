<?php

namespace App\Livewire\CarRent\Vehicle;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vehicle;

class Add extends Component
{
    use WithFileUploads;

    public $Vehicle_name = '';
    public $model = '';
    public $status = 'available';
    public $rent_price = null;
    public $image = null;
    public $description = '';
    public $is_active = true;

    protected function rules(): array
    {
        return [
            'Vehicle_name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'rent_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function removeImage(): void
    {
        $this->image = null;
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = null;
        if ($this->image && $this->image instanceof \Livewire\TemporaryUploadedFile) {
            $imagePath = $this->image->store('vehicles', 'public');
        }

        Vehicle::create([
            'Vehicle_name' => $this->Vehicle_name,
            'model' => $this->model,
            'status' => $this->status,
            'rent_price' => $this->rent_price ?: null,
            'image' => $imagePath,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
        ]);

        session()->flash('message', 'Vehicle created successfully!');
        $this->redirectRoute('vehicles.index', navigate: true);
    }
    public function render()
    {
        return view('livewire.car-rent.vehicle.add');
    }
}
