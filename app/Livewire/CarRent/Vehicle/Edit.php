<?php

namespace App\Livewire\CarRent\Vehicle;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vehicle;

class Edit extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;

    public $Vehicle_name = '';
    public $model = '';
    public $status = 'available';
    public $rent_price = null;
    public $image = null; // can hold path or TemporaryUploadedFile
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

    public function mount(Vehicle $vehicle): void
    {
        $this->vehicle = $vehicle;
        $this->Vehicle_name = $vehicle->Vehicle_name;
        $this->model = $vehicle->model;
        $this->status = $vehicle->status;
        $this->rent_price = $vehicle->rent_price;
        $this->image = $vehicle->image; // keep existing path
        $this->description = $vehicle->description;
        $this->is_active = (bool) $vehicle->is_active;
    }

    public function removeImage(): void
    {
        $this->image = null;
    }

    public function update(): void
    {
        $this->validate();

        $imagePath = is_string($this->image) ? $this->image : null;
        if ($this->image && $this->image instanceof \Livewire\TemporaryUploadedFile) {
            $imagePath = $this->image->store('vehicles', 'public');
        }

        $this->vehicle->update([
            'Vehicle_name' => $this->Vehicle_name,
            'model' => $this->model,
            'status' => $this->status,
            'rent_price' => $this->rent_price ?: null,
            'image' => $imagePath,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
        ]);

        session()->flash('message', 'Vehicle updated successfully!');
        $this->redirectRoute('vehicles.index', navigate: true);
    }
    public function render()
    {
        return view('livewire.car-rent.vehicle.edit');
    }
}
