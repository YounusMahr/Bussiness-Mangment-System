<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.udaar') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('messages.all_credit_transactions') }}</p>
            </div>
            <div class="mt-2 sm:mt-0 flex-shrink-0">
                <a 
                    wire:navigate
                    href="{{ localized_route('udaar.add') }}"
                    class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow-soft-xl"
                >
                    <i class="fas fa-plus"></i> {{ __('messages.add_udaar') }}
                </a>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500"><i class="fas fa-search"></i></span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="Search by customer name, number or notes..."
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($udaars as $udaar)
                @php
                    $customer = $udaar->customer_name ? ($customers[$udaar->customer_name] ?? null) : null;
                    $totalAmount = $udaar->paid_amount + $udaar->remaining_amount - $udaar->interest_amount;
                @endphp
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-4 mb-4">
                            <!-- Customer Image/Avatar -->
                            <div class="flex-shrink-0">
                                @if($customer && $customer->image)
                                    <img src="{{ asset('storage/'.$customer->image) }}" alt="{{ $udaar->customer_name }}" class="w-16 h-16 rounded-full object-cover border-2 border-purple-200">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center border-2 border-purple-200">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Customer Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $udaar->customer_name ?: 'Unknown Customer' }}
                                </h3>
                                @if($udaar->customer_number)
                                    <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                        <i class="fas fa-phone text-xs"></i>
                                        {{ $udaar->customer_number }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 italic mt-1">No phone number</p>
                                @endif
                            </div>
                        </div>

                        <!-- Udaar Details -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Amount:</span>
                                <span class="text-lg font-bold text-gray-900">Rs {{ number_format($totalAmount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Paid Amount:</span>
                                <span class="text-sm font-medium text-blue-600">Rs {{ number_format($udaar->paid_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Remaining:</span>
                                <span class="text-sm font-medium text-red-600">Rs {{ number_format($udaar->remaining_amount, 2) }}</span>
                            </div>
                            @if($udaar->buy_date)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Buy Date:</span>
                                    <span class="text-sm text-gray-700">{{ $udaar->buy_date->format('M d, Y') }}</span>
                                </div>
                            @endif
                            @if($udaar->due_date)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Due Date:</span>
                                    <span class="text-sm text-gray-700">{{ $udaar->due_date->format('M d, Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                            <!-- Udaar In/Out Buttons -->
                            <div class="flex gap-2">
                                <a 
                                    wire:navigate
                                    href="{{ localized_route('udaar.udaar-in', $udaar) }}"
                                    class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    title="Udaar In"
                                >
                                    <i class="fas fa-arrow-down mr-1"></i> Udaar In
                                </a>
                                <a 
                                    wire:navigate
                                    href="{{ localized_route('udaar.udaar-out', $udaar) }}"
                                    class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    title="Udaar Out"
                                >
                                    <i class="fas fa-arrow-up mr-1"></i> Udaar Out
                                </a>
                            </div>
                            
                            <!-- Other Actions -->
                            <div class="flex items-center justify-between gap-2">
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('udaar.history', $udaar) }}" 
                                    class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"
                                    title="History"
                                >
                                    <i class="fas fa-history mr-1"></i> History
                                </a>
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('udaar.edit', $udaar) }}" 
                                    class="flex-1 text-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium transition-colors"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <button 
                                    onclick="if(!confirm('{{ __('messages.delete_udaar_record') }}')) return false;" 
                                    wire:click="deleteUdaar({{ $udaar->id }})"
                                    class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"
                                    title="Delete"
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
                        <i class="fas fa-hand-holding-usd text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">{{ __('messages.no_udaar_records_found') }}</p>
                        <p class="text-gray-400 text-sm mt-2">Start by creating your first udaar record.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($udaars->hasPages())
            <div class="flex justify-center py-6 mt-6">
                {{ $udaars->links() }}
            </div>
        @endif
    </div>
</div>
