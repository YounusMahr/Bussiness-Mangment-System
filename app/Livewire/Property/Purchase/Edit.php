<?php

namespace App\Livewire\Property\Purchase;

use App\Models\PlotPurchase;
use Livewire\Component;

class Edit extends Component
{
    protected $layout = 'layouts.app';

    public $purchaseId;
    public $date;
    public $plot_area = '';
    public $plot_price = 0;
    public $installments = '';
    public $location = '';

    protected $rules = [
        'date' => 'required|date',
        'plot_area' => 'required|string|max:255',
        'plot_price' => 'required|numeric|min:0',
        'installments' => 'nullable|string',
        'location' => 'required|string',
    ];

    public function mount(PlotPurchase $purchase)
    {
        $this->purchaseId = $purchase->id;
        $this->date = $purchase->date->format('Y-m-d');
        $this->plot_area = $purchase->plot_area;
        $this->plot_price = $purchase->plot_price;
        $this->installments = $purchase->installments ?? '';
        $this->location = $purchase->location;
    }

    public function update()
    {
        $this->validate();

        $purchase = PlotPurchase::findOrFail($this->purchaseId);
        $purchase->update([
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
