<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// PWA Routes (must be before locale routes)
Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'), [
        'Content-Type' => 'application/json',
    ]);
})->name('manifest');

Route::get('/sw.js', function () {
    return response()->file(public_path('sw.js'), [
        'Content-Type' => 'application/javascript',
    ]);
})->name('service-worker');

// Language switching route (without locale prefix)
Route::get('language/{locale}', [App\Http\Controllers\LanguageController::class, 'switchLanguage'])->name('language.switch');

// Base URL redirect: send guests to localized login, authenticated to localized index
Route::get('/', function () {
    $locale = session('locale', config('app.locale', 'en'));
    if (auth()->check()) {
        return redirect("/{$locale}/index");
    }
    return redirect("/{$locale}/login");
});

// Locale root redirect: '/en' -> '/en/index' (auth) or '/en/login' (guest)
Route::get('{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'ur', 'ps'])) {
        $locale = config('app.locale', 'en');
    }
    if (auth()->check()) {
        return redirect("/{$locale}/index");
    }
    return redirect("/{$locale}/login");
})->where('locale', 'en|ur|ps');

// Public Routes - Localized
Route::group([
    'prefix' => '{locale?}',
    'middleware' => ['setLocaleFromRoute', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    
    Route::get('login', App\Livewire\Auth\Login::class)->middleware('guest')->name('login');

    // Protected Routes - Require Authentication
    Route::middleware('auth')->group(function () {
    // User Management Routes
    Route::get('/', function() {
        $locale = app()->getLocale();
        return redirect("/{$locale}/index");
    });
    Route::get('users', App\Livewire\User\Manage::class)->name('users.index');

    // Finance Routes
    Route::get('index', App\Livewire\Finance\Index::class)->name('index');
    Route::get('finance/revenue-report', App\Livewire\Finance\RevenueReport::class)->name('finance.revenue-report');
    Route::get('finance/sales-report', App\Livewire\Finance\SalesReport::class)->name('finance.sales-report');
    Route::get('finance/udhaar-report', App\Livewire\Finance\UdhaarReport::class)->name('finance.udhaar-report');
    Route::get('finance/products-report', App\Livewire\Finance\ProductsReport::class)->name('finance.products-report');
    Route::get('finance/customers-report', App\Livewire\Finance\CustomersReport::class)->name('finance.customers-report');
    
    // Grocery Routes
    Route::get('grocery/products', App\Livewire\Grocery\Products::class)->name('products');
    Route::get('products/add', App\Livewire\Grocery\AddProduct::class)->name('products.add');
    Route::get('products/{product}/edit', App\Livewire\Grocery\EditProduct::class)->name('products.edit');
    Route::get('grocery/categories', App\Livewire\Grocery\Category\Index::class)->name('categories');
    Route::get('grocery/categories/add', App\Livewire\Grocery\Category\Add::class)->name('categories.add');
    Route::get('grocery/categories/{category}/edit', App\Livewire\Grocery\Category\Edit::class)->name('categories.edit');
    Route::get('grocery/sales', App\Livewire\Grocery\Sales\Index::class)->name('sales');
    Route::get('grocery/sales/add', App\Livewire\Grocery\Sales\Add::class)->name('sales.add');
    Route::get('sales/{sale}/details', App\Livewire\Grocery\Sales\Details::class)->name('sales.details');
    Route::get('sales/{sale}/edit', App\Livewire\Grocery\Sales\Edit::class)->name('sales.edit');
    Route::get('sales/{customer}/add-sale', App\Livewire\Grocery\Sales\AddSale::class)->name('sales.add-sale');
    Route::get('grocery/udaar', App\Livewire\Grocery\Udaar\Index::class)->name('udaar.index');
    Route::get('udaar/add', App\Livewire\Grocery\Udaar\Add::class)->name('udaar.add');
    Route::get('udaar/{udaar}/edit', App\Livewire\Grocery\Udaar\Edit::class)->name('udaar.edit');
    Route::get('udaar/{udaar}/udaar-in', App\Livewire\Grocery\Udaar\UdaarIn::class)->name('udaar.udaar-in');
    Route::get('udaar/{udaar}/udaar-out', App\Livewire\Grocery\Udaar\UdaarOut::class)->name('udaar.udaar-out');
    Route::get('udaar/{udaar}/history', App\Livewire\Grocery\Udaar\History::class)->name('udaar.history');
    Route::get('grocery/low-stock', App\Livewire\Grocery\LowStock\Index::class)->name('low-stock');
    Route::get('grocery/stock-report', App\Livewire\Grocery\StockReport\Index::class)->name('stock-report');
    
    // Grocery Customer Routes
    Route::get('grocery/customers', App\Livewire\Grocery\Customer\Index::class)->name('customers.index');
    Route::get('customers/add', App\Livewire\Grocery\Customer\Add::class)->name('customers.add');
    Route::get('customers/{customer}/edit', App\Livewire\Grocery\Customer\Edit::class)->name('customers.edit');
    
    // Vehicle Customer Routes
    Route::get('vehicle/customers', App\Livewire\Vehicle\Customer\Index::class)->name('vehicle.customer.index');
    Route::get('vehicle/customers/add', App\Livewire\Vehicle\Customer\Add::class)->name('vehicle.customer.add');
    Route::get('vehicle/customers/{customer}/edit', App\Livewire\Vehicle\Customer\Edit::class)->name('vehicle.customer.edit');
    
    // Vehicle Installment Routes
    Route::get('vehicle/installments', App\Livewire\Vehicle\Installment\Index::class)->name('vehicle.installment.index');
    Route::get('vehicle/installments/add', App\Livewire\Vehicle\Installment\Add::class)->name('vehicle.installment.add');
    Route::get('vehicle/installments/{installment}/edit', App\Livewire\Vehicle\Installment\Edit::class)->name('vehicle.installment.edit');
    Route::get('vehicle/installments/{installment}/add', App\Livewire\Vehicle\Installment\InstallAdd::class)->name('vehicle.installment.install-add');
    Route::get('vehicle/installments/{installment}/return', App\Livewire\Vehicle\Installment\InstallReturn::class)->name('vehicle.installment.return');
    Route::get('vehicle/installments/{installment}/history', App\Livewire\Vehicle\Installment\History::class)->name('vehicle.installment.history');
    
    // Vehicle Report Routes
    Route::get('vehicle/report', App\Livewire\Vehicle\Report\Index::class)->name('vehicle.report.index');
    Route::get('vehicle/report/details', App\Livewire\Vehicle\Report\Details::class)->name('vehicle.report.details');
    
    // Grocery Cash Management Routes
    Route::get('grocery/cash', App\Livewire\Grocery\Cash\Index::class)->name('grocery.cash.index');
    Route::get('grocery/cash/dashboard', App\Livewire\Grocery\Cash\Dashboard::class)->name('grocery.cash.dashboard');
    Route::get('grocery/cash/add', App\Livewire\Grocery\Cash\Add::class)->name('grocery.cash.add');
    Route::get('grocery/cash/{customer}/cash-in', App\Livewire\Grocery\Cash\CashIn::class)->name('grocery.cash.cash-in');
    Route::get('grocery/cash/{customer}/cash-out', App\Livewire\Grocery\Cash\CashOut::class)->name('grocery.cash.cash-out');
    Route::get('grocery/cash/{customer}/history', App\Livewire\Grocery\Cash\History::class)->name('grocery.cash.history');

    // Grocery Stock Purchase Routes
    Route::get('grocery/purchases', App\Livewire\Grocery\Purchase\BulkPurchase::class)->name('purchases.bulk');
    Route::get('purchases/add', App\Livewire\Grocery\Purchase\NewPurchase::class)->name('purchases.add');
    Route::get('purchases/{purchase}/edit', App\Livewire\Grocery\Purchase\Update::class)->name('purchases.edit');
    Route::get('purchases/{purchase}/stock-in', App\Livewire\Grocery\Purchase\StockIn::class)->name('purchases.stock-in');
    Route::get('purchases/{purchase}/stock-out', App\Livewire\Grocery\Purchase\StockOut::class)->name('purchases.stock-out');
    Route::get('purchases/{purchase}/history', App\Livewire\Grocery\Purchase\History::class)->name('purchases.history');

    // Property Plot Purchase Routes
    Route::get('property/purchases', App\Livewire\Property\Purchase\Index::class)->name('property.purchase.index');
    Route::get('property/purchases/add', App\Livewire\Property\Purchase\Add::class)->name('property.purchase.add');
    Route::get('property/purchases/{purchase}/edit', App\Livewire\Property\Purchase\Edit::class)->name('property.purchase.edit');

    // Property Plot Sale Routes
    Route::get('property/sales', App\Livewire\Property\Sale\Index::class)->name('property.sale.index');
    Route::get('property/sales/add', App\Livewire\Property\Sale\Add::class)->name('property.sale.add');
    Route::get('property/sales/{sale}/edit', App\Livewire\Property\Sale\Edit::class)->name('property.sale.edit');

    // Property Dashboard Routes
    Route::get('property/dashboard', App\Livewire\Property\Dashboard\Report::class)->name('property.dashboard.index');
    Route::get('property/dashboard/details', App\Livewire\Property\Dashboard\Details::class)->name('property.dashboard.details');

    });
});








