<?php

namespace App\Livewire\Property\Purchase;

use App\Models\Customer;
use App\Models\PlotPurchase;
use Livewire\Component;

class Edit extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $customer_id = '';
    public $date;
    public $plot_area = '';
    public $plot_price = 0;
    public $installments = '';
    public $location = '';
    public $customers = [];

    protected $rules = [
        'customer_id' => 'nullable|exists:customers,id',
        'date' => 'required|date',
        'plot_area' => 'required|string|max:255',
        'plot_price' => 'required|numeric|min:0',
        'installments' => 'nullable|string',
        'location' => 'required|string',
    ];

    public function mount(PlotPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->customer_id = $purchase->customer_id ?? '';
        $this->date = $purchase->date->format('Y-m-d');
        $this->plot_area = $purchase->plot_area;
        $this->plot_price = $purchase->plot_price;
        $this->installments = $purchase->installments ?? '';
        $this->location = $purchase->location;
        $this->customers = Customer::where('type', 'Plot')->orderBy('name', 'asc')->get();
    }

    public function update()
    {
        $this->validate();

        $purchase = PlotPurchase::findOrFail($this->purchaseId);
        $purchase->update([
            'customer_id' => $this->customer_id ?: null,
            'date' => $this->date,
            'plot_area' => $this->plot_area,
            'plot_price' => $this->plot_price,
            'installments' => $this->installments ?: null,
            'location' => $this->location,
        ]);

        session()->flash('message', 'Plot purchase updated successfully!');
        return $this->redirectRoute('property.purchase.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.property.purchase.edit')
            ->title('Edit Plot Purchase');
    }
}
