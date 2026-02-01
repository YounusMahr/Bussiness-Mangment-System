<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.credit') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.receive_payment_for_plot') ?? 'Record payment received for plot sale' }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('property.sale.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_plot_sales') }}
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Sale Info (Read-only) -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.plot_sale_information') ?? 'Plot Sale Information' }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.customer') }}</label>
                            <input type="text" value="{{ $sale->customer_name }} ({{ $sale->customer_number }})" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                        </div>
                        @if($sale->plotPurchase)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.plot') }}</label>
                            <input type="text" value="{{ $sale->plotPurchase->plot_area ?? 'N/A' }} - {{ Str::limit($sale->plotPurchase->location ?? 'N/A', 30) }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                        </div>
                        @endif
                    </div>
                    <div class="mt-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-slate-500">{{ __('messages.total_sale_price') }}:</span>
                                <p class="text-lg font-bold text-slate-900">Rs {{ number_format($totalSalePrice, 2) }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">{{ __('messages.total_paid') }}:</span>
                                <p class="text-lg font-bold text-green-600">Rs {{ number_format($totalPaidSoFar, 2) }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">{{ __('messages.remaining') }}:</span>
                                <p class="text-lg font-bold {{ $remainingToReceive > 0 ? 'text-red-600' : 'text-slate-900' }}">Rs {{ number_format($remainingToReceive, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.payment_details') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.date') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.amount') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="amount" id="amount" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.max_payment') ?? 'Max' }}: Rs {{ number_format($maxAllowedAmount, 2) }}</p>
                            @error('amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.notes') }}</label>
                        <textarea wire:model="notes" id="notes" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="{{ __('messages.optional_notes') ?? 'Optional notes...' }}"></textarea>
                        @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('property.sale.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" {{ $remainingToReceive <= 0 ? 'disabled' : '' }} class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">{{ __('messages.credit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
