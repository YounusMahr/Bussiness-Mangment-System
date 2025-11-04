<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Language switching route (without locale prefix)
Route::get('language/{locale}', [App\Http\Controllers\LanguageController::class, 'switchLanguage'])->name('language.switch');

// Base URL redirect: send guests to localized login, authenticated to localized index
Route::get('/', function () {
    $locale = session('locale', config('app.locale', 'en'));
    if (auth()->check()) {
        return redirect()->route('index', ['locale' => $locale]);
    }
    return redirect()->route('login', ['locale' => $locale]);
});

// Locale root redirect: '/en' -> '/en/index' (auth) or '/en/login' (guest)
Route::get('{locale}', function (string $locale) {
    if (!in_array($locale, ['en', 'ur', 'ps'])) {
        $locale = config('app.locale', 'en');
    }
    if (auth()->check()) {
        return redirect()->route('index', ['locale' => $locale]);
    }
    return redirect()->route('login', ['locale' => $locale]);
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
        return redirect()->route('index', ['locale' => app()->getLocale()]);
    });
    Route::get('users', App\Livewire\User\Manage::class)->name('users.index');

    // Grocery Routes
    Route::get('index', App\Livewire\Finance\Index::class)->name('index');
    Route::get('grocery/products', App\Livewire\Grocery\Products::class)->name('products');
    Route::get('products/add', App\Livewire\Grocery\AddProduct::class)->name('products.add');
    Route::get('products/{product}/edit', App\Livewire\Grocery\EditProduct::class)->name('products.edit');
    Route::get('grocery/categories', App\Livewire\Grocery\Category\Index::class)->name('categories');
    Route::get('grocery/categories/add', App\Livewire\Grocery\Category\Add::class)->name('categories.add');
    Route::get('grocery/categories/{category}/edit', App\Livewire\Grocery\Category\Edit::class)->name('categories.edit');
    Route::get('grocery/sales', App\Livewire\Grocery\Sales\Index::class)->name('sales');
    Route::get('sales/add', App\Livewire\Grocery\Sales\Add::class)->name('sales.add');
    Route::get('sales/{sale}/edit', App\Livewire\Grocery\Sales\Edit::class)->name('sales.edit');
    Route::get('grocery/udaar', App\Livewire\Grocery\Udaar\Index::class)->name('udaar.index');
    Route::get('udaar/add', App\Livewire\Grocery\Udaar\Add::class)->name('udaar.add');
    Route::get('udaar/{udaar}/edit', App\Livewire\Grocery\Udaar\Edit::class)->name('udaar.edit');
    Route::get('grocery/stock-report', App\Livewire\Grocery\StockReport\Index::class)->name('stock-report');

    // Vehicle Routes
    Route::get('vehicles', App\Livewire\CarRent\Vehicle\Index::class)->name('vehicles.index');
    Route::get('vehicles/add', App\Livewire\CarRent\Vehicle\Add::class)->name('vehicles.add');
    Route::get('vehicles/{vehicle}/edit', App\Livewire\CarRent\Vehicle\Edit::class)->name('vehicles.edit');

    // Booking Routes
    Route::get('bookings', App\Livewire\CarRent\Booking\Index::class)->name('bookings.index');
    Route::get('bookings/add', App\Livewire\CarRent\Booking\Add::class)->name('bookings.add');
    Route::get('bookings/{booking}/edit', App\Livewire\CarRent\Booking\Edit::class)->name('bookings.edit');

    // Car Rent Udhaar Routes
    Route::get('car-rent/udaar', App\Livewire\CarRent\Udaar\Index::class)->name('car-rent.udaar.index');
    Route::get('car-rent/udaar/add', App\Livewire\CarRent\Udaar\Add::class)->name('car-rent.udaar.add');
    Route::get('car-rent/udaar/{udaar}/edit', App\Livewire\CarRent\Udaar\Edit::class)->name('car-rent.udaar.edit');
    
    // Car Rent Report Routes (if exists)
    Route::get('car-rent/report', App\Livewire\CarRent\Report\Index::class)->name('car-rent.report.index');
    });
});








