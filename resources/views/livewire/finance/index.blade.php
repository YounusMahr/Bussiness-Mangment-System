<div class="w-full px-6 py-6 mx-auto"
     x-data="{ loaded: false }"
     x-init="
       setTimeout(() => { loaded = true; }, 500);
       $dispatch('hide-loading');
     "
     x-show="loaded"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100">
    <div class="flex flex-wrap -mx-3">
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_revenue') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.total_revenue_description') ?? __('messages.overview_total_revenue') }}</p>
                            <div class="text-2xl font-bold text-gray-900 mb-2">
                                Rs {{ number_format($totalRevenue, 2) }}
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>Grocery:</span>
                                    <span class="font-semibold">Rs {{ number_format($groceryRevenue, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Car-Installment:</span>
                                    <span class="font-semibold">Rs {{ number_format($carInstallmentRevenue, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="ni ni-money-coins text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('finance.revenue-report') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_sales') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.total_sales_description') ?? __('messages.overview_total_sales') }}</p>
                            <div class="text-2xl font-bold text-gray-900 mb-2">
                                {{ number_format($totalSales) }}
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>Grocery:</span>
                                    <span class="font-semibold">{{ number_format($grocerySales) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Car-Installment:</span>
                                    <span class="font-semibold">{{ number_format($carInstallmentSales) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="ni ni-world text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('finance.sales-report') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_udhaar') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.total_udhaar_description') ?? __('messages.overview_total_udhaar') }}</p>
                            <div class="text-2xl font-bold text-gray-900 mb-2">
                                Rs {{ number_format($totalUdhaar, 2) }}
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>Grocery:</span>
                                    <span class="font-semibold">Rs {{ number_format($groceryUdhaar, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Car-Installment:</span>
                                    <span class="font-semibold">Rs {{ number_format($carInstallmentRemaining, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="ni ni-paper-diploma text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('finance.udhaar-report') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_products') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.total_products_description') ?? __('messages.overview_total_products') }}</p>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format($totalProducts) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="ni ni-cart text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('finance.products-report') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_customers') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.total_customers_description') ?? __('messages.overview_total_customers') }}</p>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format($totalCustomers) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="ni ni-single-02 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('finance.customers-report') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Cash Credit Card -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_credit') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.cash_in_total_description') ?? __('messages.total_cash_in_transactions') }}</p>
                            <div class="text-2xl font-bold text-gray-900 mb-2">
                                Rs {{ number_format($totalCashCredit, 2) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="fas fa-arrow-down text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('grocery.cash.history.all', ['type' => 'credit']) }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Cash Debit Card -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:w-1/2">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
                <div class="flex-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_debit') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('messages.cash_out_total_description') ?? __('messages.total_cash_out_transactions') }}</p>
                            <div class="text-2xl font-bold text-gray-900 mb-2">
                                Rs {{ number_format($totalCashDebit, 2) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-700 to-pink-500 text-white shadow-soft-2xl">
                                <i class="fas fa-arrow-up text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <a wire:navigate href="{{ localized_route('grocery.cash.history.all', ['type' => 'debit']) }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors shadow-soft-2xl">
                        {{ __('view_details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

