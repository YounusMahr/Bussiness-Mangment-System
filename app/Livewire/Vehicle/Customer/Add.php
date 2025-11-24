<?php

namespace App\Livewire\Vehicle\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithFileUploads;

class Add extends Component
{
    use WithFileUploads;
    
    protected $layout = 'layouts.app';

    public $name = '';
    public $number = '';
    public $email = '';
    public $image;
    public $type = 'Car-installment';
    public $address = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'number' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'image' => 'nullable|image|max:2048',
        'type' => 'required|in:Grocery,Car-installment,Plot',
        'address' => 'nullable|string',
    ];

    public function removeImage()
    {
        $this->image = null;
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('customers', 'public');
        }

        Customer::create([
            'name' => $this->name,
            'number' => $this->number,
            'email' => $this->email ?: null,
            'image' => $imagePath,
            'type' => $this->type,
            'address' => $this->address ?: null,
        ]);

        session()->flash('message', __('messages.customer_created'));
        return $this->redirectRoute('vehicle.customer.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.vehicle.customer.add')
            ->title('Add Car Installment Customer');
    }
}
