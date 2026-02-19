<div class="relative" 
     x-data="{ 
        showStatus: false,
        timer: null,
        init: function() {
            var self = this;
            // Auto-refresh connectivity every 30 seconds
            this.timer = setInterval(function() {
                if (typeof $wire !== 'undefined') {
                    $wire.refreshConnectivity();
                }
            }, 30000);
            
            // Watch for sync status changes
            $wire.on('sync-complete', function() {
                self.showStatus = true;
                setTimeout(function() { self.showStatus = false; }, 5000);
            });
        },
        destroy: function() {
            if (this.timer) clearInterval(this.timer);
        }
     }"
     @connectivity-updated.window="">
    <!-- Sync Button -->
    <button 
        type="button"
        style="background-color: #000000;"
        wire:click="sync"
        wire:loading.attr="disabled"
        @click="showStatus = true; setTimeout(() => showStatus = false, 5000)"
        @if(!$isOnline) disabled @endif
        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition-all
            {{ $isOnline && $isRemoteConnected 
                ? 'bg-green-500 hover:bg-green-600 text-white shadow-md hover:shadow-lg opacity-100 cursor-pointer' 
                : ($isOnline 
                    ? 'bg-yellow-500 hover:bg-yellow-600 text-white shadow-md hover:shadow-lg opacity-100 cursor-pointer' 
                    : 'bg-gray-400 text-white cursor-not-allowed opacity-70') 
            }}
            disabled:opacity-50 disabled:cursor-not-allowed"
        title="{{ $isOnline && $isRemoteConnected ? 'Push data to remote database' : ($isOnline ? ($connectionError ?: 'Remote database unavailable - Click to retry connection') : 'No internet connection') }}"
    >
        <i class="fas fa-upload {{ $syncing ? 'fa-spin' : '' }}"></i>
        <span class="hidden sm:inline">Push</span>
        @if($isOnline && $isRemoteConnected)
            <span class="w-2 h-2 bg-white rounded-full animate-pulse" title="Online & Connected"></span>
        @elseif($isOnline)
            <span class="w-2 h-2 bg-white rounded-full" title="Online but DB unavailable"></span>
        @else
            <span class="w-2 h-2 bg-white rounded-full opacity-50" title="Offline"></span>
        @endif
    </button>

    <!-- Status Message -->
    @if($syncStatus)
    <div 
        x-data="{ visible: true }"
        x-show="visible"
        x-init="setTimeout(() => visible = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="absolute right-0 top-full mt-2 z-50 min-w-[300px] max-w-md rounded-lg shadow-lg p-4
            {{ $syncStatus === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}"
    >
        <div class="flex items-start gap-3">
            @if($syncStatus === 'success')
                <i class="fas fa-check-circle text-green-500 text-lg mt-0.5"></i>
            @else
                <i class="fas fa-exclamation-circle text-red-500 text-lg mt-0.5"></i>
            @endif
            <div class="flex-1">
                <p class="text-sm font-semibold {{ $syncStatus === 'success' ? 'text-green-800' : 'text-red-800' }}">
                    {{ $syncStatus === 'success' ? 'Push Successful' : 'Push Failed' }}
                </p>
                @if($syncMessage)
                    <p class="text-xs mt-1 {{ $syncStatus === 'success' ? 'text-green-700' : 'text-red-700' }}">
                        {{ $syncMessage }}
                    </p>
                @endif
            </div>
            <button @click="visible = false" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Loading Overlay -->
    @if($syncing)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <div class="text-center">
                        <p class="text-lg font-semibold text-gray-800">Pushing Data...</p>
                        <p class="text-sm text-gray-600 mt-1">Please wait while we push data to remote database</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
