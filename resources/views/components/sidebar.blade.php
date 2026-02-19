<aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent flex flex-col h-[calc(100vh-2rem)] no-print">
      <div class="h-19.5 flex-shrink-0">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
          <img src="{{asset('assets/img/logo-ct.png')}}" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
          <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Waliullah-Brothers</span>
        </a>
      </div>
      
      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent flex-shrink-0" />

      <div class="items-center block w-auto overflow-y-auto overflow-x-hidden flex-1 min-h-0">
        <ul class="flex flex-col pl-0 mb-0">
          <li class="mt-0.5 w-full">
            <a wire:navigate class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors {{ request()->routeIs('index') ? 'bg-gradient-to-r from-purple-700 to-pink-500 text-white' : 'bg-white text-slate-700' }}" href="{{ localized_route('index') }}">
              <div class="{{ request()->routeIs('index') ? 'bg-white/20' : 'bg-gradient-to-tl from-purple-700 to-pink-500' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-home text-white text-xs"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.dashboard') }}</span>
            </a>
          </li>

          @php
            $isGroceryActive = request()->routeIs('categories') || request()->routeIs('categories.*') || 
                               request()->routeIs('products') || request()->routeIs('products.*') || 
                               request()->routeIs('sales') || request()->routeIs('sales.*') || 
                               request()->routeIs('udaar.*') || request()->routeIs('low-stock') || 
                               request()->routeIs('stock-report') ||
                               request()->routeIs('customers.*');
          @endphp
          <li class="mt-0.5 w-full {{ $isGroceryActive ? 'active' : '' }}">
            <div class="relative">
            <button class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 w-[85%] flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors {{ $isGroceryActive ? 'bg-gradient-to-r from-purple-700 to-pink-500 text-white' : 'bg-white text-slate-700' }}" onclick="toggleDropdown('grocery-dropdown')">
                <div class="flex items-center">
                  <div class="{{ $isGroceryActive ? 'bg-white/20' : 'bg-gradient-to-tl from-purple-700 to-pink-500' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-shopping-basket text-white text-xs"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.grocery') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 ml-auto {{ $isGroceryActive ? 'rotate-180' : '' }}" id="grocery-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <ul id="grocery-dropdown" class="mt-1 ml-10 space-y-1 {{ $isGroceryActive ? '' : 'hidden' }}" style="margin-left: 20px;">
              
               <!-- Customers -->
               <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('customers.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('customers.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-users {{ request()->routeIs('customers.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.customers') }}</span>
                  </a>
                </li>
    
              <!-- Categories (first) -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('categories') || request()->routeIs('categories.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('categories') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-tags {{ request()->routeIs('categories') || request()->routeIs('categories.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.categories') }}</span>
                  </a>
                </li>

                <!-- Products -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('products') || request()->routeIs('products.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('products') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-box {{ request()->routeIs('products') || request()->routeIs('products.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.products') }}</span>
                  </a>
                </li>

                <!-- Sales/POS -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('sales') || request()->routeIs('sales.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('sales') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-receipt {{ request()->routeIs('sales') || request()->routeIs('sales.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.sales') }}</span>
                  </a>
                </li>

                <!-- Udhaar -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('udaar.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('udaar.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-hand-holding-usd {{ request()->routeIs('udaar.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.khata') }}</span>
                  </a>
                </li>

                  <!-- Cash Management -->
                  <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('grocery.cash.index') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('grocery.cash.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-arrow-down {{ request()->routeIs('grocery.cash.index') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.credit') }}</span>
                  </a>
                </li>

                  <!-- Purchases -->
                  <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('purchases.bulk') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('purchases.bulk') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-shopping-basket {{ request()->routeIs('purchases.bulk') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.stock_purchases') }}</span>
                  </a>
                </li>
                <!-- Low Stock -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('low-stock') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('low-stock') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-exclamation-triangle {{ request()->routeIs('low-stock') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.low_stock') }}</span>
                  </a>
                </li>

                <!-- Stock Report -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('stock-report') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('stock-report') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-chart-bar {{ request()->routeIs('stock-report') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.stock_report') }}</span>
                  </a>
                </li>

               
              </ul>
            </div>
          </li>

          @php
            $isCarInstallmentActive = request()->routeIs('vehicle.customer.*') || request()->routeIs('vehicle.installment.*') || request()->routeIs('vehicle.report.*');
          @endphp
          <li class="mt-0.5 w-full {{ $isCarInstallmentActive ? 'active' : '' }}">
            <div class="relative">
            <button class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 w-[85%] flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors {{ $isCarInstallmentActive ? 'bg-gradient-to-r from-purple-700 to-pink-500 text-white' : 'bg-white text-slate-700' }}" onclick="toggleDropdown('car-installment-dropdown')">
                <div class="flex items-center">
                  <div class="{{ $isCarInstallmentActive ? 'bg-white/20' : 'bg-gradient-to-tl from-purple-700 to-pink-500' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-car text-white text-xs"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.car_installment') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 ml-auto {{ $isCarInstallmentActive ? 'rotate-180' : '' }}" id="car-installment-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <ul id="car-installment-dropdown" class="mt-1 ml-10 space-y-1 {{ $isCarInstallmentActive ? '' : 'hidden' }}" style="margin-left: 20px;">
              
                <!-- Dashboard -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('vehicle.report.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('vehicle.report.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-chart-pie {{ request()->routeIs('vehicle.report.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.dashboard') }}</span>
                  </a>
                </li>
              
                <!-- Vehicle Customers -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('vehicle.customer.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('vehicle.customer.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-users {{ request()->routeIs('vehicle.customer.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.customer') }}</span>
                  </a>
                </li>
              
                <!-- Installments -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('vehicle.installment.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('vehicle.installment.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-file-invoice-dollar {{ request()->routeIs('vehicle.installment.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.installment') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          @php
            $isPropertyActive = request()->routeIs('property.purchase.*') || request()->routeIs('property.sale.*') || request()->routeIs('property.dashboard.*') || request()->routeIs('property.customer.*');
          @endphp
          
          <li class="mt-0.5 w-full {{ $isPropertyActive ? 'active' : '' }}">
            <div class="relative">
            <button class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 w-[85%] flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors {{ $isPropertyActive ? 'bg-gradient-to-r from-purple-700 to-pink-500 text-white' : 'bg-white text-slate-700' }}" onclick="toggleDropdown('property-dropdown')">
                <div class="flex items-center">
                  <div class="{{ $isPropertyActive ? 'bg-white/20' : 'bg-gradient-to-tl from-purple-700 to-pink-500' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-map-marked-alt text-white text-xs"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.property') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 ml-auto {{ $isPropertyActive ? 'rotate-180' : '' }}" id="property-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <ul id="property-dropdown" class="mt-1 ml-10 space-y-1 {{ $isPropertyActive ? '' : 'hidden' }}" style="margin-left: 20px;">
              
                <!-- Dashboard -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('property.dashboard.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('property.dashboard.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-chart-pie {{ request()->routeIs('property.dashboard.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.dashboard') }}</span>
                  </a>
                </li>

                  <!-- Property Customers -->
                  <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('property.customer.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('property.customer.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-users {{ request()->routeIs('property.customer.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.customers') }}</span>
                  </a>
                </li>
              
                <!-- Plot Purchases -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('property.purchase.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('property.purchase.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-shopping-cart {{ request()->routeIs('property.purchase.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.plot_purchases') }}</span>
                  </a>
                </li>
                
                <!-- Plot Sales -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('property.sale.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('property.sale.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-handshake {{ request()->routeIs('property.sale.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.plot_sales') }}</span>
                  </a>
                </li>
                
              
              </ul>
            </div>
          </li>

          <li class="mt-0.5 w-full">
            <a wire:navigate class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-purple-700 to-pink-500 text-white' : 'bg-white text-slate-700' }}" href="{{ localized_route('users.index') }}">
              <div class="{{ request()->routeIs('users.*') ? 'bg-white/20' : 'bg-gradient-to-tl from-purple-700 to-pink-500' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="fas fa-user-cog text-white text-xs"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.profile') }}</span>
            </a>
          </li>
        </ul>
      </div>
     
    </aside>

    <script>
      function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const arrow = document.getElementById(dropdownId + '-arrow');
        
        if (dropdown.classList.contains('hidden')) {
          dropdown.classList.remove('hidden');
          if (arrow) arrow.classList.add('rotate-180');
        } else {
          dropdown.classList.add('hidden');
          if (arrow) arrow.classList.remove('rotate-180');
        }
      }

      // Keep dropdowns open on page load if they have active children
      document.addEventListener('DOMContentLoaded', function() {
        const groceryDropdown = document.getElementById('grocery-dropdown');
        const cashDropdown = document.getElementById('cash-dropdown');
        const propertyDropdown = document.getElementById('property-dropdown');
        
        // Check if grocery dropdown should be open
        if (groceryDropdown) {
          const activeLinks = groceryDropdown.querySelectorAll('a.bg-gradient-to-r');
          if (activeLinks.length > 0) {
            groceryDropdown.classList.remove('hidden');
            const arrow = document.getElementById('grocery-dropdown-arrow');
            if (arrow) arrow.classList.add('rotate-180');
          }
        }
        
        // Check if cash dropdown should be open
        if (cashDropdown) {
          const activeLinks = cashDropdown.querySelectorAll('a.bg-gradient-to-r');
          if (activeLinks.length > 0) {
            cashDropdown.classList.remove('hidden');
            const arrow = document.getElementById('cash-dropdown-arrow');
            if (arrow) arrow.classList.add('rotate-180');
          }
        }
        
        // Check if property dropdown should be open
        if (propertyDropdown) {
          const activeLinks = propertyDropdown.querySelectorAll('a.bg-gradient-to-r');
          if (activeLinks.length > 0) {
            propertyDropdown.classList.remove('hidden');
            const arrow = document.getElementById('property-dropdown-arrow');
            if (arrow) arrow.classList.add('rotate-180');
          }
        }
        
        // Check if car-installment dropdown should be open
        const carInstallmentDropdown = document.getElementById('car-installment-dropdown');
        if (carInstallmentDropdown) {
          const activeLinks = carInstallmentDropdown.querySelectorAll('a.bg-gradient-to-r');
          if (activeLinks.length > 0) {
            carInstallmentDropdown.classList.remove('hidden');
            const arrow = document.getElementById('car-installment-dropdown-arrow');
            if (arrow) arrow.classList.add('rotate-180');
          }
        }
        
      });
    </script>