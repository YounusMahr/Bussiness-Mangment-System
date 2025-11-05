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
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                     <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ __('messages.total_sales') }}</p>
                      <h5 class="mb-0 font-bold">
                        Rs {{ number_format($totalSales, 2) }}
                        <span class="text-sm leading-normal font-weight-bolder {{ $salesChangePct >= 0 ? 'text-lime-500' : 'text-red-500' }}">{{ $salesChangePct >= 0 ? '+' : '' }}{{ $salesChangePct }}%</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card2: Udhaar Customers -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                     <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ __('messages.udhaar_customers') }}</p>
                      <h5 class="mb-0 font-bold">
                        {{ number_format($udhaarCount) }}
                        <span class="text-sm leading-normal font-weight-bolder {{ $udhaarChange >= 0 ? 'text-lime-500' : 'text-red-500' }}">{{ $udhaarChange >= 0 ? '+' : '' }}{{ $udhaarChange }}</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card3: Inventory Quantity -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                     <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ __('messages.products_quantity') }}</p>
                      <h5 class="mb-0 font-bold">
                        {{ number_format($productsQuantity) }}
                        <span class="text-sm leading-normal font-weight-bolder {{ $soldToday > 0 ? 'text-red-600' : 'text-lime-500' }}">{{ $soldToday > 0 ? '-' : '+' }}{{ $soldToday }}</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card4: Overdue Udhaar -->
          <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                     <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ __('messages.overdue_udhaar') }}</p>
                      <h5 class="mb-0 font-bold">
                        {{ number_format($overdueUdhaar) }}
                        <span class="text-sm leading-normal font-weight-bolder {{ $overdueUdhaar > 0 ? 'text-red-600' : 'text-lime-500' }}">{{ $overdueUdhaar > 0 ? '+' : '' }}{{ $overdueUdhaar }}</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl from-purple-700 to-pink-500">
                      <i class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

</div>