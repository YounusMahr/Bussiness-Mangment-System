@props(['class' => ''])

<!-- PWA Install Button -->
<button 
    id="install-pwa-btn" 
    class="{{ $class }} hidden fixed bottom-4 right-4 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg flex items-center gap-2 z-50 transition-all duration-200"
    style="display: none;"
>
    <i class="fas fa-download"></i>
    <span>{{ __('install_app') }}</span>
</button>

<style>
    #install-pwa-btn {
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @media print {
        #install-pwa-btn {
            display: none !important;
        }
    }
</style>

