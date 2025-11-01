{{-- Loading component --}}

<div x-data="{ 
        autoHideTimeout: null,
        init() {
            // Auto-hide loading after 5 seconds as fallback
            this.autoHideTimeout = setTimeout(() => {
                if (this.$wire.loading) {
                    this.$wire.hideLoading();
                }
            }, 5000);
        },
        cleanup() {
            if (this.autoHideTimeout) {
                clearTimeout(this.autoHideTimeout);
            }
        }
    }" 
    x-init="init()" 
    x-on:destroy="cleanup()">
    
    @if($loading)
        <!-- Default Professional Loader -->
        @if($loadingType === 'default')
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-95 backdrop-blur-sm" 
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
              
              <div class="text-center">
                <!-- Soft UI Loading Spinner -->
                <div class="relative mx-auto mb-6">
                  <div class="w-16 h-16 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-2xl shadow-soft-xl flex items-center justify-center animate-pulse">
                    <div class="w-8 h-8 border-3 border-white border-t-transparent rounded-full animate-spin"></div>
                  </div>
                  
                  <!-- Floating particles -->
                  <div class="absolute -top-1 -left-1 w-3 h-3 bg-purple-400 rounded-full animate-ping opacity-75"></div>
                  <div class="absolute -bottom-1 -right-1 w-2 h-2 bg-pink-400 rounded-full animate-ping opacity-75" style="animation-delay: 0.5s;"></div>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $loadingText }}</h3>
                <p class="text-sm text-gray-500">Please wait while we prepare your content...</p>
                
                <!-- Progress indicator -->
                <div class="mt-6 w-48 mx-auto h-1 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-purple-600 to-pink-500 rounded-full animate-pulse" 
                       style="width: 0%; animation: progress 2s ease-in-out infinite;"></div>
                </div>
              </div>
            </div>
        @endif

        <!-- Minimal Loader -->
        @if($loadingType === 'minimal')
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90" 
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
              
              <div class="text-center">
                <div class="w-12 h-12 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-2xl shadow-soft-xl flex items-center justify-center mx-auto mb-4">
                  <div class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </div>
                <p class="text-sm font-medium text-gray-600">{{ $loadingText }}</p>
              </div>
            </div>
        @endif

        <!-- Page Transition Loader -->
        @if($loadingType === 'page-transition')
            <div class="fixed inset-0 z-50 bg-gradient-to-br from-purple-50 to-pink-50" 
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
              
              <div class="flex items-center justify-center h-full">
                <div class="text-center">
                  <!-- Animated Logo -->
                  <div class="relative mx-auto mb-6">
                    <div class="w-20 h-20 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-3xl shadow-soft-2xl flex items-center justify-center transform animate-bounce">
                      <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </div>
                    
                    <!-- Floating particles -->
                    <div class="absolute -top-2 -left-2 w-4 h-4 bg-purple-400 rounded-full animate-ping opacity-75"></div>
                    <div class="absolute -bottom-2 -right-2 w-3 h-3 bg-pink-400 rounded-full animate-ping opacity-75" style="animation-delay: 0.5s;"></div>
                    <div class="absolute top-0 -right-4 w-2 h-2 bg-purple-300 rounded-full animate-ping opacity-75" style="animation-delay: 1s;"></div>
                  </div>
                  
                  <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                  <p class="text-gray-600">{{ $loadingText }}</p>
                  
                  <!-- Animated Progress Dots -->
                  <div class="flex justify-center mt-6 space-x-2">
                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                    <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                  </div>
                </div>
              </div>
            </div>
        @endif
    @endif
    
    <style>
    @keyframes progress {
      0% { width: 0%; }
      50% { width: 70%; }
      100% { width: 100%; }
    }
    </style>
</div>
