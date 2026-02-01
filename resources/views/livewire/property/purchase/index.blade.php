<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.plot_purchases') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('messages.manage_plot_purchase_records') }}</p>
            </div>
            <div>
                <a 
                    wire:navigate
                    href="{{ localized_route('property.purchase.add') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-medium rounded-lg shadow-md transition-colors"
                >
                    <i class="fas fa-plus"></i>
                    {{ __('messages.add_plot_purchase') }}
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
                            placeholder="{{ __('messages.search_plot_purchases') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Plot Purchases Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($purchases as $purchase)
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Plot Info -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                                    <i class="fas fa-map-marked-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $purchase->plot_area }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-calendar text-xs"></i>
                                        {{ $purchase->date->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Plot Details -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.location') }}:</span>
                                <span class="text-sm font-medium text-gray-900 max-w-[150px] truncate" title="{{ $purchase->location }}">{{ $purchase->location }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.plot_price') }}:</span>
                                <span class="text-sm font-bold text-gray-900">Rs {{ number_format($purchase->plot_price, 2) }}</span>
                            </div>
                            @php
                                $totalPaid = (float)($purchase->total_paid ?? 0);
                                $remaining = max((float)$purchase->plot_price - $totalPaid, 0);
                            @endphp
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.total_paid') }}:</span>
                                <span class="text-sm font-medium text-green-600">Rs {{ number_format($totalPaid, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.remaining') }}:</span>
                                <span class="text-sm font-bold {{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }}">Rs {{ number_format($remaining, 2) }}</span>
                            </div>
                            @if($purchase->installments)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">{{ __('messages.installments') }}:</span>
                                    <span class="text-sm font-medium text-gray-900 max-w-[150px] truncate" title="{{ $purchase->installments }}">{{ Str::limit($purchase->installments, 30) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                            <!-- Debit (Pay for plot) -->
                            <a 
                                wire:navigate
                                href="{{ localized_route('property.purchase.out', $purchase) }}"
                                class="block w-full text-center px-3 py-2 bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-600 text-white text-sm font-medium rounded-lg transition-colors"
                                title="{{ __('messages.debit') }} ({{ __('messages.pay_for_plot') ?? 'Pay for plot' }})"
                            >
                                <i class="fas fa-arrow-up mr-1"></i> {{ __('messages.debit') }}
                            </a>
                            
                            <!-- Other Actions -->
                            <div class="flex items-center justify-between gap-2">
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('property.purchase.history', $purchase) }}" 
                                    class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.view_history') }}"
                                >
                                    <i class="fas fa-history mr-1"></i> {{ __('messages.history') }}
                                </a>
                            <a 
                                wire:navigate
                                href="{{ localized_route('property.purchase.edit', $purchase) }}"
                                    class="flex-1 text-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.edit') }}"
                            >
                                    <i class="fas fa-edit mr-1"></i> {{ __('messages.edit') }}
                            </a>
                            <button 
                                wire:click="confirmDelete({{ $purchase->id }})"
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
                        <i class="fas fa-map-marked-alt text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.no_plot_purchases_found') }}</h3>
                        <p class="text-gray-500 mb-4">{{ __('messages.get_started_by_creating_first_plot_purchase') }}</p>
                        <a wire:navigate href="{{ localized_route('property.purchase.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                            {{ __('messages.add_plot_purchase') }}
                        </a>
                    </div>
                    </div>
                @endforelse
            </div>

        <!-- Pagination -->
            @if($purchases->hasPages())
            <div class="flex justify-center py-6 mt-6">
                    {{ $purchases->links() }}
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
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('messages.delete_plot_purchase') }}?</h3>
                    <p class="text-slate-600 text-sm">{{ __('messages.are_you_sure_delete_plot_purchase') }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">{{ __('messages.yes_delete') }}</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">{{ __('messages.cancel') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
