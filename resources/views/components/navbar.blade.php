<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav x-data="{
              crumbs: [],
              currentTitle: '',
              init() {
                const makeLabel = (s) => {
                  const labels = {
                    'index': 'Dashboard',
                    'users': 'Profile',
                    'grocery': 'Grocery',
                    'products': 'Products',
                    'categories': 'Categories',
                    'sales': 'Sales',
                    'udaar': 'Udhaar',
                    'stock-report': 'Stock Report',
                    'vehicles': 'Vehicles',
                    'bookings': 'Bookings',
                    'car-rent': 'Car Rent',
                    'report': 'Report',
                    'add': 'Add',
                    'edit': 'Edit'
                  };
                  return labels[s.toLowerCase()] || s.replace(/[-_]/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                };
                const build = () => {
                  const path = window.location.pathname;
                  const segments = path.split('/').filter(Boolean);
                  let built = '';
                  this.crumbs = [];
                  
                  // Always start with Dashboard
                  this.crumbs.push({ label: 'Dashboard', url: '/index' });
                  
                  // Skip if already at dashboard
                  if (segments.length === 0 || (segments.length === 1 && segments[0] === 'index')) {
                    this.currentTitle = 'Dashboard';
                    return;
                  }
                  
                  // Build breadcrumbs from path segments
                  for (const seg of segments) {
                    if (/^\d+$/.test(seg)) continue; // skip numeric ids
                    if (seg === 'index') continue; // skip index segment
                    built += '/' + seg;
                    this.crumbs.push({ label: makeLabel(seg), url: built });
                  }
                  
                  this.currentTitle = this.crumbs[this.crumbs.length - 1].label;
                };
                
                // Initial build
                build();
                
                // Update on browser back/forward
                window.addEventListener('popstate', build);
                
                // Update on Livewire navigation - use multiple strategies
                window.addEventListener('livewire:navigated', () => {
                  setTimeout(build, 100);
                });
                
                // Also listen for hashchange and other navigation events
                window.addEventListener('hashchange', build);
                
                // Poll for URL changes as fallback (every 500ms)
                setInterval(() => {
                  const currentPath = window.location.pathname;
                  if (currentPath !== this.lastPath) {
                    this.lastPath = currentPath;
                    build();
                  }
                }, 500);
                
                this.lastPath = window.location.pathname;
              }
            }" x-init="init()">
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <template x-for="(crumb, i) in crumbs" :key="i">
                <template x-if="i === 0">
                  <li class="text-sm leading-normal">
                    <a wire:navigate class="opacity-50 text-slate-700 hover:opacity-75 transition-opacity" :href="crumb.url" x-text="crumb.label"></a>
                  </li>
                </template>
              </template>
              <template x-for="(crumb, i) in crumbs" :key="'c-'+i">
                <template x-if="i > 0">
                  <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']">
                    <template x-if="i < crumbs.length - 1">
                      <a wire:navigate class="opacity-70 hover:opacity-100 transition-opacity" :href="crumb.url" x-text="crumb.label"></a>
                    </template>
                    <template x-if="i === crumbs.length - 1">
                      <span aria-current="page" class="font-semibold" x-text="crumb.label"></span>
                    </template>
                  </li>
                </template>
              </template>
            </ol>
            <h6 class="mb-0 font-bold capitalize" x-text="currentTitle"></h6>
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
                <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Type here..." />
              </div>
            </div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
              <!-- Calculator btn  -->
              <li class="flex items-center" x-data="{ calculatorOpen: false, closeCalculator() { this.calculatorOpen = false; } }">
                <button @click="calculatorOpen = true" class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro border-fuchsia-500 ease-soft-in hover:scale-102 active:shadow-soft-xs text-fuchsia-500 hover:border-fuchsia-500 active:bg-fuchsia-500 active:hover:text-fuchsia-500 hover:text-fuchsia-500 tracking-tight-soft hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent">
                  <i class="fas fa-calculator mr-2"></i>Calculator
                </button>

                <!-- Calculator Modal -->
                <div x-show="calculatorOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click.away="calculatorOpen = false"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                  <div @click.stop
                       x-data="{
                         display: '0',
                         previousValue: null,
                         operation: null,
                         waitingForNewValue: false,
                         
                         appendNumber(number) {
                           if (this.waitingForNewValue) {
                             this.display = number === '.' ? '0.' : number.toString();
                             this.waitingForNewValue = false;
                           } else {
                             if (number === '.') {
                               if (!this.display.includes('.')) {
                                 this.display = this.display + '.';
                               }
                             } else {
                               this.display = this.display === '0' ? number.toString() : this.display + number;
                             }
                           }
                         },
                         
                         setOperation(op) {
                           const inputValue = parseFloat(this.display);
                           
                           if (this.previousValue === null) {
                             this.previousValue = inputValue;
                           } else if (this.operation) {
                             const result = this.calculate();
                             // Format display to remove trailing zeros
                             this.display = result.toString().replace(/\.?0+$/, '');
                             this.previousValue = result;
                           }
                           
                           this.waitingForNewValue = true;
                           this.operation = op;
                         },
                         
                         calculate() {
                           const inputValue = parseFloat(this.display);
                           
                           if (this.previousValue === null) return inputValue;
                           
                           let result;
                           switch (this.operation) {
                             case '+': result = this.previousValue + inputValue; break;
                             case '-': result = this.previousValue - inputValue; break;
                             case '*': result = this.previousValue * inputValue; break;
                             case '/': result = inputValue !== 0 ? this.previousValue / inputValue : 0; break;
                             default: result = inputValue;
                           }
                           
                           // Format result to avoid long decimal strings
                           return Math.round(result * 100000000) / 100000000;
                         },
                         
                         performCalculation() {
                           if (this.operation && this.previousValue !== null) {
                             const result = this.calculate();
                             // Format display to remove trailing zeros
                             this.display = result.toString().replace(/\.?0+$/, '');
                             this.previousValue = null;
                             this.operation = null;
                             this.waitingForNewValue = true;
                           }
                         },
                         
                         clear() {
                           this.display = '0';
                           this.previousValue = null;
                           this.operation = null;
                           this.waitingForNewValue = false;
                         },
                         
                         clearEntry() {
                           this.display = '0';
                           this.waitingForNewValue = false;
                         },
                         
                         deleteLast() {
                           if (this.display.length > 1) {
                             this.display = this.display.slice(0, -1);
                           } else {
                             this.display = '0';
                           }
                         }
                       }"
                       class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
                    <!-- Calculator Header -->
                    <div class="flex justify-between items-center mb-4">
                      <h3 class="text-xl font-bold text-slate-700">Calculator</h3>
                      <button @click="$parent.calculatorOpen = false" class="text-slate-500 hover:text-slate-700 text-2xl font-bold">&times;</button>
                    </div>
                    
                    <!-- Display -->
                    <div class="mb-4">
                      <input type="text" 
                             x-model="display" 
                             readonly 
                             class="w-full px-4 py-4 text-right text-3xl font-bold bg-slate-100 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 text-slate-800">
                    </div>
                    
                    <!-- Buttons Grid -->
                    <div class="grid grid-cols-4 gap-3">
                      <!-- Row 1 -->
                      <button @click="clear()" class="col-span-2 px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition-all active:scale-95">Clear</button>
                      <button @click="deleteLast()" class="px-4 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-all active:scale-95">⌫</button>
                      <button @click="setOperation('/')" class="px-4 py-3 bg-fuchsia-500 hover:bg-fuchsia-600 text-white font-semibold rounded-lg transition-all active:scale-95">÷</button>
                      
                      <!-- Row 2 -->
                      <button @click="appendNumber(7)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">7</button>
                      <button @click="appendNumber(8)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">8</button>
                      <button @click="appendNumber(9)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">9</button>
                      <button @click="setOperation('*')" class="px-4 py-3 bg-fuchsia-500 hover:bg-fuchsia-600 text-white font-semibold rounded-lg transition-all active:scale-95">×</button>
                      
                      <!-- Row 3 -->
                      <button @click="appendNumber(4)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">4</button>
                      <button @click="appendNumber(5)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">5</button>
                      <button @click="appendNumber(6)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">6</button>
                      <button @click="setOperation('-')" class="px-4 py-3 bg-fuchsia-500 hover:bg-fuchsia-600 text-white font-semibold rounded-lg transition-all active:scale-95">−</button>
                      
                      <!-- Row 4 -->
                      <button @click="appendNumber(1)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">1</button>
                      <button @click="appendNumber(2)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">2</button>
                      <button @click="appendNumber(3)" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">3</button>
                      <button @click="setOperation('+')" class="px-4 py-3 bg-fuchsia-500 hover:bg-fuchsia-600 text-white font-semibold rounded-lg transition-all active:scale-95">+</button>
                      
                      <!-- Row 5 -->
                      <button @click="appendNumber(0)" class="col-span-2 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">0</button>
                      <button @click="appendNumber('.')" class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg transition-all active:scale-95">.</button>
                      <button @click="performCalculation()" class="px-4 py-3 bg-gradient-to-tl from-purple-700 to-pink-500 hover:from-purple-600 hover:to-pink-400 text-white font-bold rounded-lg transition-all active:scale-95 text-xl">=</button>
                    </div>
                  </div>
                </div>
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
                            <h6 class="mb-0 text-sm font-normal leading-normal">Profile</h6>
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
                            <h6 class="mb-0 text-sm font-normal leading-normal">Log Out</h6>
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
                    <span class="hidden sm:inline">Sign In</span>
                  </a>
                </li>
                <li class="flex items-center pl-2">
                  <a href="" class="block px-0 py-2 text-sm font-semibold transition-all ease-nav-brand text-slate-500">
                    <i class="fa fa-user-plus sm:mr-1"></i>
                    <span class="hidden sm:inline">Sign Up</span>
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
                <a href="javascript:;" class="p-0 text-sm transition-all ease-nav-brand text-slate-500">
                  <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                  <!-- fixed-plugin-button-nav  -->
                </a>
              </li>

              <!-- notifications -->

              <li class="relative flex items-center pr-2">
                <p class="hidden transform-dropdown-show"></p>
                <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500" dropdown-trigger aria-expanded="false">
                  <i class="cursor-pointer fa fa-bell"></i>
                </a>

                <ul dropdown-menu class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease-soft lg:shadow-soft-3xl duration-250 min-w-44 before:sm:right-7.5 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-solid border-transparent bg-white bg-clip-padding px-2 py-4 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:left-auto before:top-0 before:z-50 before:inline-block before:font-normal before:text-white before:antialiased before:transition-all before:content-['\f0d8'] sm:-mr-6 lg:absolute lg:right-0 lg:left-auto lg:mt-2 lg:block lg:cursor-pointer">
                  <!-- add show class on dropdown open js -->
                  <li class="relative mb-2">
                    <a class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg bg-transparent px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors" href="javascript:;">
                      <div class="flex py-1">
                        <div class="my-auto">
                          <img src="{{ asset('assets/img/team-2.jpg') }}" class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 max-w-none rounded-xl" />
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 text-sm font-normal leading-normal"><span class="font-semibold">New message</span> from Laur</h6>
                          <p class="mb-0 text-xs leading-tight text-slate-400">
                            <i class="mr-1 fa fa-clock"></i>
                            13 minutes ago
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                  <li class="relative mb-2">
                    <a class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700" href="javascript:;">
                      <div class="flex py-1">
                        <div class="my-auto">
                          <img src="{{ asset('assets/img/small-logos/logo-spotify.svg') }}" class="inline-flex items-center justify-center mr-4 text-sm text-white bg-gradient-to-tl from-gray-900 to-slate-800 h-9 w-9 max-w-none rounded-xl" />
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 text-sm font-normal leading-normal"><span class="font-semibold">New album</span> by Travis Scott</h6>
                          <p class="mb-0 text-xs leading-tight text-slate-400">
                            <i class="mr-1 fa fa-clock"></i>
                            1 day
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>

                  <li class="relative">
                    <a class="ease-soft py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 transition-colors duration-300 hover:bg-gray-200 hover:text-slate-700" href="javascript:;">
                      <div class="flex py-1">
                        <div class="inline-flex items-center justify-center my-auto mr-4 text-sm text-white transition-all duration-200 ease-nav-brand bg-gradient-to-tl from-slate-600 to-slate-300 h-9 w-9 rounded-xl">
                          <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>credit-card</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                              <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                  <g transform="translate(453.000000, 454.000000)">
                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                  </g>
                                </g>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                          <h6 class="mb-1 text-sm font-normal leading-normal">Payment successfully completed</h6>
                          <p class="mb-0 text-xs leading-tight text-slate-400">
                            <i class="mr-1 fa fa-clock"></i>
                            2 days
                          </p>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>
