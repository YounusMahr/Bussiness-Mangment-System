<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $search = "";
    public $suggestions = [];
    public $selectedIndex = -1;

    public function updatedSearch($value)
    {
        if (strlen($value) < 1) {
            $this->suggestions = [];
            $this->selectedIndex = -1;
            return;
        }

        $this->suggestions = $this->getSuggestions($value);
        $this->selectedIndex = -1;
    }

    private function getSuggestions($query)
    {
        $suggestions = [];
        $query = strtolower($query);

        // 1. Static Menu Items (only generate URL if matched)
        $menuSource = [
            ['label' => __('messages.dashboard'), 'route' => 'index', 'icon' => 'fas fa-home'],
            ['label' => __('messages.customers'), 'route' => 'customers.index', 'icon' => 'fas fa-users'],
            ['label' => __('messages.categories'), 'route' => 'categories', 'icon' => 'fas fa-tags'],
            ['label' => __('messages.products'), 'route' => 'products', 'icon' => 'fas fa-box'],
            ['label' => __('messages.sales'), 'route' => 'sales', 'icon' => 'fas fa-receipt'],
            ['label' => __('messages.khata'), 'route' => 'udaar.index', 'icon' => 'fas fa-hand-holding-usd'],
            ['label' => __('messages.credit'), 'route' => 'grocery.cash.index', 'icon' => 'fas fa-arrow-down'],
            ['label' => __('messages.stock_purchases'), 'route' => 'purchases.bulk', 'icon' => 'fas fa-shopping-basket'],
            ['label' => __('messages.low_stock'), 'route' => 'low-stock', 'icon' => 'fas fa-exclamation-triangle'],
            ['label' => __('messages.stock_report'), 'route' => 'stock-report', 'icon' => 'fas fa-chart-bar'],
            ['label' => __('messages.car_installment'), 'route' => 'vehicle.report.index', 'icon' => 'fas fa-car'],
            ['label' => __('messages.vehicle_customers'), 'route' => 'vehicle.customer.index', 'icon' => 'fas fa-users'],
            ['label' => __('messages.installment'), 'route' => 'vehicle.installment.index', 'icon' => 'fas fa-file-invoice-dollar'],
            ['label' => __('messages.property'), 'route' => 'property.dashboard.index', 'icon' => 'fas fa-map-marked-alt'],
            ['label' => __('messages.property_customers'), 'route' => 'property.customer.index', 'icon' => 'fas fa-users'],
            ['label' => __('messages.plot_purchases'), 'route' => 'property.purchase.index', 'icon' => 'fas fa-shopping-cart'],
            ['label' => __('messages.plot_sales'), 'route' => 'property.sale.index', 'icon' => 'fas fa-handshake'],
            ['label' => __('messages.profile'), 'route' => 'users.index', 'icon' => 'fas fa-user-cog'],
        ];

        foreach ($menuSource as $item) {
            if (str_contains(strtolower($item['label']), $query)) {
                $suggestions[] = [
                    'label' => $item['label'],
                    'url' => localized_route($item['route']),
                    'type' => 'menu',
                    'icon' => $item['icon']
                ];
            }
        }

        // 2. Database records (limiting for performance)
        // Products
        $products = \App\Models\Product::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        foreach ($products as $product) {
            $suggestions[] = [
                'label' => $product->name,
                'url' => localized_route('products', ['search' => $product->name]),
                'type' => 'product',
                'icon' => 'fas fa-box-open',
                'subtext' => __('messages.product')
            ];
        }

        // Customers
        $customers = \App\Models\Customer::where('name', 'like', "%{$query}%")
            ->orWhere('number', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        foreach ($customers as $customer) {
            $suggestions[] = [
                'label' => $customer->name,
                'url' => localized_route('customers.index', ['search' => $customer->name]),
                'type' => 'customer',
                'icon' => 'fas fa-user',
                'subtext' => __('messages.customer')
            ];
        }

        // Categories
        $categories = \App\Models\Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get();
        foreach ($categories as $category) {
            $suggestions[] = [
                'label' => $category->name,
                'url' => localized_route('categories', ['search' => $category->name]),
                'type' => 'category',
                'icon' => 'fas fa-tag',
                'subtext' => __('messages.category')
            ];
        }

        return array_slice($suggestions, 0, 10);
    }

    public function selectSuggestion($index)
    {
        if (isset($this->suggestions[$index])) {
            $suggestion = $this->suggestions[$index];
            $this->search = $suggestion['label'];
            $this->suggestions = [];
            return $this->redirect($suggestion['url'], navigate: true);
        }
    }

    public function incrementIndex()
    {
        if ($this->selectedIndex < count($this->suggestions) - 1) {
            $this->selectedIndex++;
        } else {
            $this->selectedIndex = 0;
        }
    }

    public function decrementIndex()
    {
        if ($this->selectedIndex > 0) {
            $this->selectedIndex--;
        } else {
            $this->selectedIndex = count($this->suggestions) - 1;
        }
    }

    public function performSearch()
    {
        if ($this->selectedIndex !== -1 && isset($this->suggestions[$this->selectedIndex])) {
            return $this->selectSuggestion($this->selectedIndex);
        }

        if (trim($this->search)) {
            $locale = app()->getLocale();
            return $this->redirect("/{$locale}/grocery/products?search=" . urlencode($this->search), navigate: true);
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        $locale = session("locale", config("app.locale", "en"));
        return $this->redirect(route("login", ["locale" => $locale]), navigate: true);
    }

    public function render()
    {
        return view("livewire.components.navbar");
    }
}
