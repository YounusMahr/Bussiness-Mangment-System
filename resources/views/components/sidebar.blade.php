<aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-transparent">
      <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
          <img src="{{asset('assets/img/logo-ct.png')}}" class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />
          <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Bussiness-MS</span>
        </a>
      </div>

      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

      <div class="items-center block w-auto h-full overflow-auto h-sidenav grow basis-full" style="height: 100vh;">
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
                               request()->routeIs('udaar.*') || request()->routeIs('stock-report');
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
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.udaar') }}</span>
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
            $isCarRentActive = request()->routeIs('vehicles.*') || request()->routeIs('bookings.*') || 
                               request()->routeIs('car-rent.udaar.*') || request()->routeIs('car-rent.report.*');
          @endphp
          <li class="mt-0.5 w-full {{ $isCarRentActive ? 'active' : '' }}">
            <div class="relative">
            <button class="py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 w-[85%] flex items-center whitespace-nowrap rounded-lg px-4 font-semibold transition-colors {{ $isCarRentActive ? 'bg-gradient-to-r from-purple-700 to-pink-500 text-white' : 'bg-white text-slate-700' }}" onclick="toggleDropdown('car-rent-dropdown')">
                <div class="flex items-center">
                  <div class="{{ $isCarRentActive ? 'bg-white/20' : 'bg-gradient-to-tl from-purple-700 to-pink-500' }} shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                    <i class="fas fa-car text-white text-xs"></i>
                  </div>
                  <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.car_rent') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 ml-auto {{ $isCarRentActive ? 'rotate-180' : '' }}" id="car-rent-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
              
              <!-- Dropdown Menu -->
              <ul id="car-rent-dropdown" class="mt-1 space-y-1 {{ $isCarRentActive ? '' : 'hidden' }}" style="margin-left: 20px;">
                <!-- Vehicles -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('vehicles.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('vehicles.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-car-side {{ request()->routeIs('vehicles.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.vehicles') }}</span>
                  </a>
                </li>
                
                <!-- Bookings -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('bookings.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('bookings.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-calendar-check {{ request()->routeIs('bookings.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.bookings') }}</span>
                  </a>
                </li>
                
                <!-- Car Rent Udhaar -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('car-rent.udaar.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('car-rent.udaar.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-hand-holding-usd {{ request()->routeIs('car-rent.udaar.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.car_rent') }} {{ __('messages.udaar') }}</span>
                  </a>
                </li>
                
                <!-- Car Rent Report -->
                <li class="w-full">
                  <a wire:navigate class="py-2 text-xs ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-3 transition-colors rounded-lg {{ request()->routeIs('car-rent.report.*') ? 'bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50' }}" href="{{ localized_route('car-rent.report.index') }}">
                    <div class="shadow-soft-2xl mr-2 flex h-6 w-6 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center">
                      <i class="fas fa-chart-line {{ request()->routeIs('car-rent.report.*') ? 'text-purple-600' : 'text-slate-600' }} text-xs"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ __('messages.car_rent') }} {{ __('messages.report') }}</span>
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
        const carRentDropdown = document.getElementById('car-rent-dropdown');
        
        // Check if grocery dropdown should be open
        if (groceryDropdown) {
          const activeLinks = groceryDropdown.querySelectorAll('a.bg-gradient-to-r');
          if (activeLinks.length > 0) {
            groceryDropdown.classList.remove('hidden');
            const arrow = document.getElementById('grocery-dropdown-arrow');
            if (arrow) arrow.classList.add('rotate-180');
          }
        }
        
        // Check if car rent dropdown should be open
        if (carRentDropdown) {
          const activeLinks = carRentDropdown.querySelectorAll('a.bg-gradient-to-r');
          if (activeLinks.length > 0) {
            carRentDropdown.classList.remove('hidden');
            const arrow = document.getElementById('car-rent-dropdown-arrow');
            if (arrow) arrow.classList.add('rotate-180');
          }
        }
      });
    </script>