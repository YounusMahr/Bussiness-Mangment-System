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
      <!-- card1: Total Profit -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_profit') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.total_profit_description') }}</p>
                <div class="text-2xl font-bold {{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                  Rs {{ number_format($totalProfit, 2) }}
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-indigo-500 flex items-center justify-center">
                  <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('vehicle.report.details') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('messages.view_details') }}
            </a>
          </div>
        </div>
      </div>

      <!-- card2: Total Sales -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_sales') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.total_sales_description') }}</p>
                <div class="text-2xl font-bold text-blue-600">
                  Rs {{ number_format($totalSales, 2) }}
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-indigo-500 flex items-center justify-center">
                  <i class="fas fa-money-bill-wave text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('vehicle.report.details') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('messages.view_details') }}
            </a>
          </div>
        </div>
      </div>

      <!-- card3: Total Installments -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_installments') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.total_installments_description') }}</p>
                <div class="text-2xl font-bold text-blue-600">
                  {{ number_format($totalInstallments) }} {{ __('messages.installments') }}
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-indigo-500 flex items-center justify-center">
                  <i class="fas fa-car text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('messages.view_details') }}
            </a>
          </div>
        </div>
      </div>

      <!-- card4: Total Remaining -->
      <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/2">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border my-4 mx-2">
          <div class="flex-auto p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('messages.total_remaining') }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ __('messages.total_remaining_description') }}</p>
                <div class="text-2xl font-bold text-red-600">
                  Rs {{ number_format($totalRemaining, 2) }}
                </div>
              </div>
              <div class="ml-4">
                <div class="inline-block w-16 h-16 text-center rounded-lg bg-gradient-to-tl from-blue-600 to-indigo-500 flex items-center justify-center">
                  <i class="fas fa-exclamation-circle text-2xl text-white"></i>
                </div>
              </div>
            </div>
            <a wire:navigate href="{{ localized_route('vehicle.report.details') }}" class="block w-full mt-4 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white text-center font-semibold rounded-lg transition-colors">
              {{ __('messages.view_details') }}
            </a>
          </div>
        </div>
      </div>
    </div>

</div>
