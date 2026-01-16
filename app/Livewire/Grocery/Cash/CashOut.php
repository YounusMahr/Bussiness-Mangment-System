<?php

namespace App\Livewire\Grocery\Cash;

use App\Models\Customer;
use App\Models\GroceryCashTransaction;
use Livewire\Component;

class CashOut extends Component
{
    protected $layout = 'layouts.app';

    public $customerId;
    public $customer;
    public $date;
    public $available_balance = 0;
    public $returned_amount = 0;
    public $remaining_balance = 0;
    public $excess_amount = 0; // Amount that will be added to cash-in
    public $status = 'pending';
    public $notes = '';

    protected $rules = [
        'date' => 'required|date',
        'available_balance' => 'required|numeric|min:0',
        'returned_amount' => 'required|numeric|min:0',
        'remaining_balance' => 'nullable|numeric',
        'status' => 'required|in:pending,returned',
        'notes' => 'nullable|string',
    ];

    public function mount(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->customer = $customer;
        $this->date = now()->format('Y-m-d');
        
        // Calculate available balance from cash-in transactions
        $totalCashIn = (float)(GroceryCashTransaction::where('customer_id', $customer->id)
            ->where('type', 'cash-in')
            ->sum('return_amount') ?? 0);
        
        $totalCashOut = (float)(GroceryCashTransaction::where('customer_id', $customer->id)
            ->where('type', 'cash-out')
            ->sum('returned_amount') ?? 0);
        
        $this->available_balance = $totalCashIn - $totalCashOut;
        $this->calculateRemainingBalance();
    }

    public function updatedReturnedAmount()
    {
        $this->calculateRemainingBalance();
    }

    public function calculateRemainingBalance()
    {
        $available = (float)($this->available_balance ?? 0);
        $returned = (float)($this->returned_amount ?? 0);
        
        // Calculate remaining balance (can be negative if overpaid)
        $this->remaining_balance = $available - $returned;
        
        // Calculate excess amount (amount that will be added to cash-in)
        if ($returned > $available) {
            $this->excess_amount = $returned - $available;
        } else {
            $this->excess_amount = 0;
        }
    }

    public function save()
    {
        $this->validate();
        $this->calculateRemainingBalance();

        // Calculate the actual returned amount (only up to available balance)
        $actualReturned = min((float)$this->returned_amount, (float)$this->available_balance);
        $actualRemaining = max((float)$this->remaining_balance, 0);

        // Create cash-out transaction
        GroceryCashTransaction::create([
            'date' => $this->date,
            'customer_id' => $this->customerId,
            'type' => 'cash-out',
            'available_balance' => $this->available_balance,
            'returned_amount' => $actualReturned,
            'remaining_balance' => $actualRemaining,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        // If there's excess amount (overpayment), create a cash-in transaction
        if ($this->excess_amount > 0) {
            GroceryCashTransaction::create([
                'date' => $this->date,
                'customer_id' => $this->customerId,
                'type' => 'cash-in',
                'invest_cash' => $this->excess_amount,
                'interest' => 0,
                'return_amount' => $this->excess_amount,
                'status' => 'returned',
                'notes' => 'Auto-created from cash-out overpayment: ' . ($this->notes ?: 'Excess amount returned'),
            ]);

            $excessMessage = str_replace(':amount', number_format($this->excess_amount, 2), __('messages.excess_amount_added_to_cash_in'));
            session()->flash('message', __('messages.cash_out_created') . ' ' . $excessMessage);
        } else {
            session()->flash('message', __('messages.cash_out_created'));
        }

        return $this->redirectRoute('grocery.cash.index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.grocery.cash.cash-out')
            ->title('Add Cash Out');
    }
}
