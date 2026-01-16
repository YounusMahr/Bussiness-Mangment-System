<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.cash_management') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('messages.manage_customer_cash_transactions') }}</p>
            </div>
            <div>
                <a 
                    wire:navigate
                    href="{{ localized_route('grocery.cash.add') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white font-medium rounded-lg shadow-md transition-colors"
                >
                    <i class="fas fa-plus"></i>
                    {{ __('add_record') }}
                </a>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
        @endif

        <!-- Total Credit and Debit Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('messages.total_credit') }}</p>
                            <h3 class="text-2xl font-bold text-gray-900">Rs {{ number_format($totalCredit, 2) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.cash_in_transactions') }}</p>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-emerald-400 flex items-center justify-center">
                            <i class="fas fa-arrow-down text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-pink-500 h-2"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('messages.total_debit') }}</p>
                            <h3 class="text-2xl font-bold text-gray-900">Rs {{ number_format($totalDebit, 2) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.cash_out_transactions') }}</p>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-400 to-pink-400 flex items-center justify-center">
                            <i class="fas fa-arrow-up text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500"><i class="fas fa-search"></i></span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="{{ __('messages.search_customers') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($customers as $customer)
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-4 mb-4">
                            <!-- Customer Image/Avatar -->
                            <div class="flex-shrink-0">
                                @if($customer->image)
                                    <img src="{{ asset('storage/'.$customer->image) }}" alt="{{ $customer->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-green-200">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-emerald-400 flex items-center justify-center border-2 border-green-200">
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

                        <!-- Cash Details -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.total_amount') }}:</span>
                                <span class="text-lg font-bold {{ $customer->total_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rs {{ number_format($customer->total_amount, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.cash_in') }}:</span>
                                <span class="text-sm font-medium text-green-600">Rs {{ number_format($customer->total_cash_in, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.cash_out') }}:</span>
                                <span class="text-sm font-medium text-red-600">Rs {{ number_format($customer->total_cash_out, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.status') }}:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->status === 'returned' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                    {{ $customer->status === 'returned' ? __('messages.returned') : __('messages.pending') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                            <!-- Credit/Debit Buttons -->
                            <div class="flex gap-2">
                                <a 
                                    wire:navigate
                                     href="{{ localized_route('grocery.cash.cash-out', $customer) }}"
                                    class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    title="Credit (Payment In)"
                                >
                                    <i class="fas fa-arrow-down mr-1"></i> Credit
                                </a>
                                <a 
                                    wire:navigate
                                    href="{{ localized_route('grocery.cash.cash-in', $customer) }}"
                                    class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    title="Debit (Payment Out)"
                                >
                                    <i class="fas fa-arrow-up mr-1"></i> Debit
                                </a>
                            </div>
                            
                            <!-- Other Actions -->
                            <div class="flex items-center justify-between gap-2">
                                <a 
                                    wire:navigate
                                    href="{{ localized_route('grocery.cash.history', $customer) }}"
                                    class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.history') }}"
                                >
                                    <i class="fas fa-history mr-1"></i> {{ __('messages.history') }}
                                </a>
                                <button 
                                    wire:click="confirmDelete({{ $customer->id }})" 
                                    class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.delete') }}"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white shadow-soft-xl rounded-2xl p-12 text-center">
                        <i class="fas fa-wallet text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.no_customers_found') }}</h3>
                        <p class="text-gray-500 mb-4">{{ __('messages.no_customers_with_cash_transactions') }}</p>
                        <a wire:navigate href="{{ localized_route('grocery.cash.add') }}" class="bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white font-bold py-2 px-4 rounded-lg">
                            {{ __('messages.add_record') }}
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

    <!-- Delete Confirmation Modal -->
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
                    <button wire:click="deleteCustomer({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">{{ __('messages.yes_delete') }}</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">{{ __('messages.cancel') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
