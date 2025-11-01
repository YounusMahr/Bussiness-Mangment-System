<div class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90 backdrop-blur-sm" 
     x-data="{ show: false }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">
  
  <!-- Main Loading Container -->
  <div class="relative">
    <!-- Outer Ring -->
    <div class="w-16 h-16 border-4 border-gray-200 rounded-full animate-spin border-t-purple-600"></div>
    
    <!-- Inner Ring -->
    <div class="absolute top-2 left-2 w-12 h-12 border-4 border-gray-100 rounded-full animate-spin border-t-pink-500" style="animation-direction: reverse; animation-duration: 0.8s;"></div>
    
    <!-- Center Dot -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gradient-to-r from-purple-600 to-pink-500 rounded-full animate-pulse"></div>
  </div>
  
  <!-- Loading Text -->
  <div class="absolute top-24 left-1/2 transform -translate-x-1/2">
    <p class="text-sm font-medium text-gray-600 animate-pulse">Loading...</p>
  </div>
  
  <!-- Progress Bar -->
  <div class="absolute bottom-32 left-1/2 transform -translate-x-1/2 w-48 h-1 bg-gray-200 rounded-full overflow-hidden">
    <div class="h-full bg-gradient-to-r from-purple-600 to-pink-500 rounded-full animate-pulse" 
         style="width: 0%; animation: progress 2s ease-in-out infinite;"></div>
  </div>
</div>

<!-- Alternative Minimal Loading -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-95" 
     x-data="{ show: false }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     style="display: none;"
     id="minimal-loader">
  
  <div class="text-center">
    <!-- Soft UI Loading Spinner -->
    <div class="relative mx-auto mb-4">
      <div class="w-12 h-12 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-2xl shadow-soft-xl flex items-center justify-center">
        <div class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
      </div>
    </div>
    
    <h3 class="text-lg font-semibold text-gray-700 mb-2">Loading</h3>
    <p class="text-sm text-gray-500">Please wait while we prepare your dashboard...</p>
  </div>
</div>

<!-- Page Transition Overlay -->
<div class="fixed inset-0 z-40 bg-gradient-to-br from-purple-50 to-pink-50" 
     x-data="{ show: false }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;"
     id="page-transition">
  
  <div class="flex items-center justify-center h-full">
    <div class="text-center">
      <!-- Animated Logo/Icon -->
      <div class="relative mx-auto mb-6">
        <div class="w-20 h-20 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-3xl shadow-soft-2xl flex items-center justify-center transform animate-bounce">
          <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        
        <!-- Floating Particles -->
        <div class="absolute -top-2 -left-2 w-4 h-4 bg-purple-400 rounded-full animate-ping opacity-75"></div>
        <div class="absolute -bottom-2 -right-2 w-3 h-3 bg-pink-400 rounded-full animate-ping opacity-75" style="animation-delay: 0.5s;"></div>
        <div class="absolute top-0 -right-4 w-2 h-2 bg-purple-300 rounded-full animate-ping opacity-75" style="animation-delay: 1s;"></div>
      </div>
      
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
      <p class="text-gray-600">Setting up your dashboard...</p>
      
      <!-- Animated Progress Dots -->
      <div class="flex justify-center mt-6 space-x-2">
        <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce"></div>
        <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
        <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes progress {
  0% { width: 0%; }
  50% { width: 70%; }
  100% { width: 100%; }
}

/* Custom animations for professional feel */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>

<script>
// Global loading functions
window.showLoader = function(type = 'default') {
  const loader = document.getElementById(type === 'minimal' ? 'minimal-loader' : 'page-transition');
  if (loader) {
    loader.style.display = 'block';
    loader._x_dataStack[0].show = true;
  }
};

window.hideLoader = function(type = 'default') {
  const loader = document.getElementById(type === 'minimal' ? 'minimal-loader' : 'page-transition');
  if (loader) {
    loader._x_dataStack[0].show = false;
    setTimeout(() => {
      loader.style.display = 'none';
    }, 300);
  }
};

// Auto-hide loader after 3 seconds (fallback)
setTimeout(() => {
  window.hideLoader();
}, 3000);
</script>
