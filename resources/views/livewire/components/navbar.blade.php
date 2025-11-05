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
              <li class="flex items-center" 
                  x-data="{ 
                    calculatorOpen: false, 
                    closeCalculator() { this.calculatorOpen = false; },
                    init() {
                      this.$watch('calculatorOpen', value => {
                        if (value) {
                          document.body.style.overflow = 'hidden';
                        } else {
                          document.body.style.overflow = '';
                        }
                      });
                    }
                  }"
                  @close-calculator.window="calculatorOpen = false">
                <button @click="calculatorOpen = true" class="inline-block px-8 py-2 mb-0 mr-4 text-xs font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro border-fuchsia-500 ease-soft-in hover:scale-102 active:shadow-soft-xs text-fuchsia-500 hover:border-fuchsia-500 active:bg-fuchsia-500 active:hover:text-fuchsia-500 hover:text-fuchsia-500 tracking-tight-soft hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent">
                  <i class="fas fa-calculator mr-2"></i>Cal
                </button>

                <!-- Calculator Modal -->
                <div x-show="calculatorOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 backdrop-blur-none"
                     x-transition:enter-end="opacity-100 backdrop-blur-md"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 backdrop-blur-md"
                     x-transition:leave-end="opacity-0 backdrop-blur-none"
                     @click.away="calculatorOpen = false"
                     @keydown.escape.window="calculatorOpen = false"
                     class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/30 backdrop-blur-md">
                  <div @click.stop
                       x-data="{
                         display: '0',
                         expressionParts: [],
                         waitingForNewValue: false,
                         showAC: true,
                         showResult: false,
                         
                         appendNumber(number) {
                           if (this.waitingForNewValue) {
                             this.display = number === '.' ? '0.' : number.toString();
                             this.waitingForNewValue = false;
                             this.showAC = false;
                             this.showResult = false;
                           } else {
                             if (number === '.') {
                               if (!this.display.includes('.')) {
                                 this.display = this.display + '.';
                               }
                             } else if (number === '00') {
                               if (this.display !== '0') {
                                 this.display = this.display + '00';
                               }
                             } else {
                               this.display = this.display === '0' ? number.toString() : this.display + number;
                             }
                             this.showAC = false;
                             this.showResult = false;
                           }
                         },
                         
                         setOperation(op) {
                           const inputValue = parseFloat(this.display);
                           const opSymbol = { '+': '+', '-': '−', '*': '×', '/': '÷' }[op] || op;
                           
                           if (this.showResult) {
                             // Starting new calculation from result
                             this.expressionParts = [this.display, op];
                             this.display = '0';
                           } else {
                             // Add current display and operation to expression
                             if (this.expressionParts.length === 0) {
                               // First operation
                               this.expressionParts = [this.display, op];
                               this.display = '0';
                             } else {
                               // Check if last item is an operation (can replace it, but first add current number if waiting)
                               const lastItem = this.expressionParts[this.expressionParts.length - 1];
                               if (typeof lastItem === 'string' && ['+', '-', '*', '/'].includes(lastItem)) {
                                 // Last item is operation, but we may have a number in display
                                 // If we're waiting for new value, just replace operation
                                 if (this.waitingForNewValue) {
                                   // Just replace operation (user changed their mind on operation)
                                   this.expressionParts[this.expressionParts.length - 1] = op;
                                 } else {
                                   // Add current display and new operation
                                   this.expressionParts.push(this.display, op);
                                   this.display = '0';
                                 }
                               } else {
                                 // Last item is a number, add current display and operation
                                 this.expressionParts.push(this.display, op);
                                 this.display = '0';
                               }
                             }
                           }
                           
                           this.waitingForNewValue = true;
                           this.showAC = false;
                           this.showResult = false;
                         },
                         
                         buildExpressionFromParts(parts) {
                           return parts.map((p, i) => 
                             i % 2 === 0 ? p : this.getOpSymbol(p)
                           ).join(' ');
                         },
                         
                         getOpSymbol(op) {
                           const symbols = { '+': '+', '-': '−', '*': '×', '/': '÷' };
                           return symbols[op] || op;
                         },
                         
                         getExpressionDisplay() {
                           if (this.expressionParts.length === 0) return '';
                           if (this.showResult) return '';
                           
                           // Build expression from parts
                           let expr = this.buildExpressionFromParts(this.expressionParts);
                           
                           // If last item is an operation, show expression as is
                           const lastItem = this.expressionParts[this.expressionParts.length - 1];
                           const isLastOp = typeof lastItem === 'string' && ['+', '-', '*', '/'].includes(lastItem);
                           
                           if (isLastOp || this.waitingForNewValue) {
                             return expr;
                           }
                           
                           // If last item is a number, show expression without it + current display
                           if (this.expressionParts.length > 1) {
                             const parts = this.expressionParts.slice(0, -1);
                             return this.buildExpressionFromParts(parts) + ' ' + this.display;
                           }
                           
                           return expr;
                         },
                         
                         performCalculation() {
                           if (this.expressionParts.length === 0) return;
                           
                           // Check if last item is an operation
                           const lastItem = this.expressionParts[this.expressionParts.length - 1];
                           const isLastOperation = typeof lastItem === 'string' && ['+', '-', '*', '/'].includes(lastItem);
                           
                           if (isLastOperation) {
                             // Last item is an operation, add current display
                             this.expressionParts.push(this.display);
                           } else if (this.expressionParts.length > 0 && !this.showResult) {
                             // Last item is a number, update it with current display
                             this.expressionParts[this.expressionParts.length - 1] = this.display;
                           }
                           
                           // Evaluate expression
                           const result = this.evaluateExpression();
                           
                           // Show only result (expression cleared automatically by showResult flag)
                           this.display = this.formatResult(result);
                           
                           // Reset for next calculation
                           this.expressionParts = [];
                             this.waitingForNewValue = true;
                           this.showAC = true;
                           this.showResult = true;
                         },
                         
                         evaluateExpression() {
                           // Clone array to avoid mutation
                           const parts = [...this.expressionParts];
                           
                           // Handle multiplication and division first (PEMDAS)
                           for (let i = 1; i < parts.length; i += 2) {
                             if (parts[i] === '*' || parts[i] === '/') {
                               const left = parseFloat(parts[i - 1]);
                               const right = parseFloat(parts[i + 1]);
                               const result = parts[i] === '*' ? left * right : (right !== 0 ? left / right : 0);
                               parts.splice(i - 1, 3, result);
                               i -= 2; // Adjust index after removal
                             }
                           }
                           
                           // Handle addition and subtraction
                           let result = parseFloat(parts[0]);
                           for (let i = 1; i < parts.length; i += 2) {
                             const op = parts[i];
                             const num = parseFloat(parts[i + 1]);
                             if (op === '+') {
                               result += num;
                             } else if (op === '-') {
                               result -= num;
                             }
                           }
                           
                           return Math.round(result * 100000000) / 100000000;
                         },
                         
                         formatResult(num) {
                           let formatted = num.toString().replace(/\.?0+$/, '');
                           if (formatted.length > 12) {
                             formatted = num.toExponential(8);
                           }
                           return formatted;
                         },
                         
                         allClear() {
                           this.display = '0';
                           this.expressionParts = [];
                           this.waitingForNewValue = false;
                           this.showAC = true;
                           this.showResult = false;
                         },
                         
                         clearEntry() {
                           if (this.expressionParts.length > 0) {
                           this.display = '0';
                           this.waitingForNewValue = false;
                             this.showAC = false;
                             this.showResult = false;
                           } else {
                             this.allClear();
                           }
                         },
                         
                         toggleSign() {
                           if (this.display !== '0' && this.display !== '') {
                             const value = parseFloat(this.display);
                             this.display = (-value).toString().replace(/\.?0+$/, '') || '0';
                           }
                         },
                         
                         percentage() {
                           const value = parseFloat(this.display);
                           this.display = (value / 100).toString().replace(/\.?0+$/, '');
                         }
                       }"
                       class="bg-gradient-to-br from-gray-900/95 via-black/90 to-gray-900/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 p-6 w-full max-w-sm">
                    <!-- Calculator Header -->
                    <div class="flex justify-between items-center mb-2">
                      <h3 class="text-lg font-semibold text-white">{{ __('messages.calculator') }}</h3>
                      <button @click="$dispatch('close-calculator')" class="text-white/70 hover:text-white text-2xl font-light transition-colors cursor-pointer leading-none">&times;</button>
                    </div>
                    
                    <!-- Display -->
                    <div class="mb-6">
                      <!-- Expression Display -->
                      <div x-show="getExpressionDisplay()" 
                           class="text-right text-lg text-white/60 mb-2 min-h-[24px] font-light"
                           x-text="getExpressionDisplay()"></div>
                      <!-- Result Display -->
                      <input type="text" 
                             x-model="display" 
                             readonly 
                             class="w-full px-4 py-3 text-right text-5xl font-light bg-transparent text-white focus:outline-none"
                             style="font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;">
                    </div>
                    
                    <!-- Buttons Grid -->
                    <div class="grid grid-cols-4 gap-3">
                      <!-- Row 1: AC/C, ±, %, ÷ -->
                      <template x-if="showAC">
                        <button @click="allClear()" class="h-16 bg-gradient-to-br from-gray-400/80 to-gray-500/80 hover:from-gray-400 hover:to-gray-500 active:from-gray-500 active:to-gray-600 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">AC</button>
                      </template>
                      <template x-if="!showAC">
                        <button @click="clearEntry()" class="h-16 bg-gradient-to-br from-gray-400/80 to-gray-500/80 hover:from-gray-400 hover:to-gray-500 active:from-gray-500 active:to-gray-600 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">C</button>
                      </template>
                      <button @click="toggleSign()" class="h-16 bg-gradient-to-br from-gray-400/80 to-gray-500/80 hover:from-gray-400 hover:to-gray-500 active:from-gray-500 active:to-gray-600 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">±</button>
                      <button @click="percentage()" class="h-16 bg-gradient-to-br from-gray-400/80 to-gray-500/80 hover:from-gray-400 hover:to-gray-500 active:from-gray-500 active:to-gray-600 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">%</button>
                      <button @click="setOperation('/')" class="h-16 bg-gradient-to-br from-orange-500/90 to-orange-600/90 hover:from-orange-400 hover:to-orange-500 active:from-orange-600 active:to-orange-700 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">÷</button>
                      
                      <!-- Row 2: 7, 8, 9, × -->
                      <button @click="appendNumber(7)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">7</button>
                      <button @click="appendNumber(8)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">8</button>
                      <button @click="appendNumber(9)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">9</button>
                      <button @click="setOperation('*')" class="h-16 bg-gradient-to-br from-orange-500/90 to-orange-600/90 hover:from-orange-400 hover:to-orange-500 active:from-orange-600 active:to-orange-700 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">×</button>
                      
                      <!-- Row 3: 4, 5, 6, − -->
                      <button @click="appendNumber(4)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">4</button>
                      <button @click="appendNumber(5)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">5</button>
                      <button @click="appendNumber(6)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">6</button>
                      <button @click="setOperation('-')" class="h-16 bg-gradient-to-br from-orange-500/90 to-orange-600/90 hover:from-orange-400 hover:to-orange-500 active:from-orange-600 active:to-orange-700 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">−</button>
                      
                      <!-- Row 4: 1, 2, 3, + -->
                      <button @click="appendNumber(1)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">1</button>
                      <button @click="appendNumber(2)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">2</button>
                      <button @click="appendNumber(3)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">3</button>
                      <button @click="setOperation('+')" class="h-16 bg-gradient-to-br from-orange-500/90 to-orange-600/90 hover:from-orange-400 hover:to-orange-500 active:from-orange-600 active:to-orange-700 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">+</button>
                      
                      <!-- Row 5: 0, 00, ., = -->
                      <button @click="appendNumber(0)" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">0</button>
                      <button @click="appendNumber('00')" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">00</button>
                      <button @click="appendNumber('.')" class="h-16 bg-gradient-to-br from-gray-600/70 to-gray-700/70 hover:from-gray-600 hover:to-gray-700 active:from-gray-700 active:to-gray-800 backdrop-blur-sm border border-white/10 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">.</button>
                      <button @click="performCalculation()" class="h-16 bg-gradient-to-br from-orange-500/90 to-orange-600/90 hover:from-orange-400 hover:to-orange-500 active:from-orange-600 active:to-orange-700 backdrop-blur-sm border border-white/20 text-white text-2xl font-medium rounded-full transition-all active:scale-95 flex items-center justify-center shadow-lg">=</button>
                    </div>
                  </div>
                </div>
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
      </nav>
