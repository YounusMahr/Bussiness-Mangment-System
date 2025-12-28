<?php

namespace App\Livewire\Property\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;
    
    protected $layout = 'layouts.app';

    public $customerId;
    public $name = '';
    public $number = '';
    public $email = '';
    public $image;
    public $oldImage;
    public $type = 'Plot';
    public $address = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'number' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'image' => 'nullable|image|max:2048',
        'type' => 'required|in:Grocery,Car-installment,Plot',
        'address' => 'nullable|string',
    ];

    public function mount(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->number = $customer->number;
        $this->email = $customer->email ?? '';
        $this->oldImage = $customer->image;
        $this->type = $customer->type;
        $this->address = $customer->address ?? '';
    }

    public function removeImage()
    {
        $this->image = null;
        if ($this->oldImage) {
            Storage::disk('public')->delete($this->oldImage);
            $this->oldImage = null;
        }
    }

    public function update()
    {
        $this->validate();

        $customer = Customer::findOrFail($this->customerId);

        $imagePath = $this->oldImage;
        if ($this->image) {
            // Delete old image if exists
            if ($this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('customers', 'public');
        }

        $customer->update([
            'name' => $this->name,
            'number' => $this->number,
            'email' => $this->email ?: null,
            'image' => $imagePath,
            'type' => $this->type,
            'address' => $this->address ?: null,
        ]);

        session()->flash('message', __('messages.customer_updated'));
        return $this->redirectRoute('property.customer.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.customer.edit')
            ->title('Edit Property Customer');
    }
}
