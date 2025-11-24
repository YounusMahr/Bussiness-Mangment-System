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
    <!-- row 1 -->
    <div class="flex flex-wrap -mx-3">
      <!-- card1: Total Sales -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_sales') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.total_sales_description') ?? __('messages.overview_total_sales') }}</p>
                <div class="text-2xl font-bold text-gray-900">
                  Rs {{ number_format($totalSales, 2) }}
                  <span class="text-sm font-normal {{ $salesChangePct >= 0 ? 'text-green-600' : 'text-red-600' }} ml-2">
                    ({{ $salesChangePct >= 0 ? '+' : '' }}{{ $salesChangePct }}%)
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500 flex items-center justify-center">
                  <i class="ni ni-money-coins text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('sales') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('view_details') }}
            </a>
          </div>
        </div>
      </div>

      <!-- card2: Udhaar Customers -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.udhaar_customers') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.udhaar_customers_description') ?? __('messages.overview_udhaar_customers') }}</p>
                <div class="text-2xl font-bold text-purple-600">
                  {{ number_format($udhaarCount) }}
                  <span class="text-sm font-normal {{ $udhaarChange >= 0 ? 'text-green-600' : 'text-red-600' }} ml-2">
                    ({{ $udhaarChange >= 0 ? '+' : '' }}{{ $udhaarChange }})
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500 flex items-center justify-center">
                  <i class="ni ni-world text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('udaar.index') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('view_details') }}
            </a>
          </div>
        </div>
      </div>

      <!-- card3: Inventory Quantity -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.products_quantity') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.products_quantity_description') ?? __('messages.overview_products_quantity') }}</p>
                <div class="text-2xl font-bold text-purple-600">
                  {{ number_format($productsQuantity) }}
                  <span class="text-sm font-normal {{ $soldToday > 0 ? 'text-red-600' : 'text-green-600' }} ml-2">
                    ({{ $soldToday > 0 ? '-' : '+' }}{{ $soldToday }})
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500 flex items-center justify-center">
                  <i class="ni ni-paper-diploma text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('products') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('view_details') }}
            </a>
          </div>
        </div>
      </div>

      <!-- card4: Overdue Udhaar -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.overdue_udhaar') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.overdue_udhaar_description') ?? __('messages.overview_overdue_udhaar') }}</p>
                <div class="text-2xl font-bold {{ $overdueUdhaar > 0 ? 'text-red-600' : 'text-green-600' }}">
                  {{ number_format($overdueUdhaar) }}
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500 flex items-center justify-center">
                  <i class="ni ni-cart text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('udaar.index') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('view_details') }}
            </a>
          </div>
        </div>
      </div>
    </div>

</div>