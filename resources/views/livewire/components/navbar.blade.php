@php
$translations = [
    'dashboard' => __('messages.dashboard'),
    'profile' => __('messages.profile'),
    'grocery' => __('messages.grocery'),
    'products' => __('messages.products'),
    'categories' => __('messages.categories'),
    'sales' => __('messages.sales'),
    'udaar' => __('messages.udaar'),
    'stock_report' => __('messages.stock_report'),
    'vehicles' => __('messages.vehicles'),
    'bookings' => __('messages.bookings'),
    'car_rent' => __('messages.car_rent'),
    'report' => __('messages.report'),
    'add' => __('messages.add'),
    'edit' => __('messages.edit')
];
@endphp
<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true"
     >
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav >
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
             <li class="text-lg leading-normal hidden md:block xl:block">
              <a wire:navigate class="opacity-75 font-bold text-slate-700  transition-opacity" href="/index">Dashboard</a>
             </li>
              
            </ol>
            
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto hidden md:block xl:block md:pr-4">
              <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
                <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="{{ __('messages.type_here') }}" />
              </div>
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <!-- Calculator btn  -->
              <li class="flex items-center">
                <button type="button"
                        onclick="Livewire.dispatch('toggle-calculator')"
                        class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro border-fuchsia-500 ease-soft-in hover:scale-102 active:shadow-soft-xs text-fuchsia-500 hover:border-fuchsia-500 active:bg-fuchsia-500 active:hover:text-fuchsia-500 hover:text-fuchsia-500 tracking-tight-soft hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent">
                  <i class="fas fa-calculator mr-2"></i>Cal
                </button>
              </li>
              <!-- Language Selector -->
              <li class="relative flex items-center pr-2" x-data="{ languageOpen: false }">
                <p class="hidden transform-dropdown-show"></p>
                <a href="javascript:;" 
                   @click="languageOpen = !languageOpen"
                   class="block p-0 text-sm transition-all ease-nav-brand text-slate-500" 
                   aria-expanded="false">
                  <div class="flex items-center">
                    <i class="fas fa-language mr-2"></i>
                    <span class="text-xs font-semibold hidden sm:inline">{{ __('messages.language') }}</span>
                    <span class="text-xs font-semibold sm:hidden">Lang</span>
                  </div>
                </a>

                <ul x-show="languageOpen" 
                    x-cloak
                    @click.away="languageOpen = false"
                    class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease-soft lg:shadow-soft-3xl duration-250 min-w-44 before:sm:right-7.5 before:text-5.5 absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    style="display: none;">
                  @php
                    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
                    $currentLocale = LaravelLocalization::getCurrentLocale();
                  @endphp
                  <li class="relative mb-2">
                    <a href="{{ route('language.switch', 'en') }}" 
                       class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors {{ $currentLocale === 'en' ? 'bg-gray-100 font-semibold' : '' }}">
                      <div class="flex items-center justify-between">
                        <span class="text-sm">{{ __('messages.english') }}</span>
                        @if($currentLocale === 'en')
                          <i class="fas fa-check text-fuchsia-500"></i>
                        @endif
                      </div>
                    </a>
                  </li>
                  <li class="relative mb-2">
                    <a href="{{ route('language.switch', 'ur') }}" 
                       class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors {{ $currentLocale === 'ur' ? 'bg-gray-100 font-semibold' : '' }}">
                      <div class="flex items-center justify-between">
                        <span class="text-sm">{{ __('messages.urdu') }}</span>
                        @if($currentLocale === 'ur')
                          <i class="fas fa-check text-fuchsia-500"></i>
                        @endif
                      </div>
                    </a>
                  </li>
                  <li class="relative">
                    <a href="{{ route('language.switch', 'ps') }}" 
                       class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors {{ $currentLocale === 'ps' ? 'bg-gray-100 font-semibold' : '' }}">
                      <div class="flex items-center justify-between">
                        <span class="text-sm">{{ __('messages.pashto') }}</span>
                        @if($currentLocale === 'ps')
                          <i class="fas fa-check text-fuchsia-500"></i>
                        @endif
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
              @auth
                <!-- User Dropdown -->
                <li class="relative flex items-center pr-2">
                  <p class="hidden transform-dropdown-show"></p>
                  <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500" dropdown-trigger aria-expanded="false">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                      </div>
                      <span class="ml-2 text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                      <i class="fa fa-chevron-down ml-1 text-xs"></i>
                    </div>
                  </a>

                  <ul dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease-soft lg:shadow-soft-3xl duration-250 min-w-44 before:sm:right-7.5 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                    <li class="relative mb-2">
                      <a wire:navigate href="{{ route('users.index') }}" class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors">
                        <div class="flex py-1">
                          <div class="my-auto">
                            <i class="fa fa-user mr-3 text-slate-400"></i>
                          </div>
                          <div class="flex flex-col justify-center">
                            <h6 class="mb-0 text-sm font-normal leading-normal">{{ __('messages.profile') }}</h6>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li class="relative">
                      <button wire:click="logout" class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700 text-left">
                        <div class="flex py-1">
                          <div class="my-auto">
                            <i class="fa fa-sign-out-alt mr-3 text-slate-400"></i>
                          </div>
                          <div class="flex flex-col justify-center">
                            <h6 class="mb-0 text-sm font-normal leading-normal">{{ __('messages.log_out') }}</h6>
                          </div>
                        </div>
                      </button>
                    </li>
                  </ul>
                </li>
              @else
                <li class="flex items-center">
                  <a href="" class="block px-0 py-2 text-sm font-semibold transition-all ease-nav-brand text-slate-500">
                    <i class="fa fa-user sm:mr-1"></i>
                    <span class="hidden sm:inline">{{ __('messages.sign_in') }}</span>
                  </a>
                </li>
                <li class="flex items-center pl-2">
                  <a href="" class="block px-0 py-2 text-sm font-semibold transition-all ease-nav-brand text-slate-500">
                    <i class="fa fa-user-plus sm:mr-1"></i>
                    <span class="hidden sm:inline">{{ __('messages.sign_up') }}</span>
                  </a>
                </li>
              @endauth
              <li class="flex items-center pl-4 xl:hidden">
                <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500" sidenav-trigger>
                  <div class="w-4.5 overflow-hidden">
                    <i class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                    <i class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                    <i class="ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                  </div>
                </a>
              </li>
              <li class="flex items-center px-4">
              </li>

              <!-- notifications -->

           
            </ul>
          </div>
        </div>
        @livewire('components.calculator')
      </nav>
     