<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.customers_management') }}</h1>
                </div>
                <div class="flex justify-between w-full">
                <p class="text-gray-600 mt-1">{{ __('messages.manage_your_customers') }}</p>
                    <a 
                        wire:navigate
                        href="{{ localized_route('vehicle.customer.add') }}" 
                        class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white md:font-bold font-normal py-1 px-2 md:py-2 md:px-2 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-plus"></i>
                        {{ __('messages.add_customer') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex  gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                            <i class="fas fa-search"></i>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="{{ __('messages.search_customers') }}..."
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-full leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
                <div>
                    <button 
                        style="background-color:green;"
                        wire:click="printTable" 
                        class="bg-green-200 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Customers Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($customers as $customer)
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-4 mb-4">
                            <!-- Customer Image/Avatar -->
                            <div class="flex-shrink-0">
                                @if($customer->image)
                                    <img src="{{ asset('storage/'.$customer->image) }}" alt="{{ $customer->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-purple-200">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center border-2 border-purple-200">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Customer Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $customer->name }}
                                </h3>
                                <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                    <i class="fas fa-phone text-xs"></i>
                                    {{ $customer->number }}
                                </p>
                            </div>
                        </div>

                        <!-- Customer Details -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            @if($customer->email)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-envelope text-xs"></i>
                                        Email:
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 truncate ml-2">{{ $customer->email }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Type:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $customer->type }}
                                </span>
                            </div>
                            @if($customer->address)
                                <div class="flex justify-between items-start">
                                    <span class="text-sm text-gray-600">Address:</span>
                                    <span class="text-sm text-gray-700 text-right max-w-xs truncate" title="{{ $customer->address }}">
                                        {{ Str::limit($customer->address, 40) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 flex items-center justify-between gap-2">
                            <a 
                                wire:navigate 
                                href="{{ localized_route('vehicle.customer.edit', $customer) }}" 
                                class="flex-1 text-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium transition-colors"
                                title="Edit"
                            >
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button 
                                wire:click="confirmDelete({{ $customer->id }})"
                                class="flex-1 px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"
                                title="Delete"
                            >
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white shadow-soft-xl rounded-2xl p-12 text-center">
                        <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.no_customers_found') }}</h3>
                        <p class="text-gray-500 mb-4">{{ __('messages.get_started_by_creating_first_customer') }}</p>
                        <a wire:navigate href="{{ localized_route('vehicle.customer.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                            {{ __('messages.add_customer') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($customers->hasPages())
            <div class="flex justify-center py-6 mt-6">
                {{ $customers->links() }}
            </div>
        @endif
    </div>


    {{-- Confirmation Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
                <div class="mb-5">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('messages.delete_customer') }}?</h3>
                    <p class="text-slate-600 text-sm">{{ __('messages.are_you_sure_delete_customer') }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">{{ __('messages.yes_delete') }}</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">{{ __('messages.cancel') }}</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
    
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    
    th, td {
        border: 1px solid #000 !important;
        padding: 8px !important;
    }
    
    .bg-gradient-to-r {
        background:rgb(5, 241, 80) !important;
    }
}
</style>

<!-- Print JavaScript -->
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('print-table', () => {
        window.print();
    });
});
</script>


</div>


