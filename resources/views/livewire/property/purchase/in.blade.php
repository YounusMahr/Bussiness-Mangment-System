<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Record Credit</h1>
                    <p class="text-slate-600 mt-1">Record payment received for plot purchase</p>
                </div>
                <a wire:navigate href="{{ localized_route('property.purchase.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    Back to Purchases
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <!-- Card header bar -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Purchase Information (Read-only) -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Plot Purchase Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Customer</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <input type="text" value="{{ $purchase->customer ? $purchase->customer->name . ' (' . $purchase->customer->number . ')' : 'N/A' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Plot Area</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-ruler"></i></span>
                                <input type="text" value="{{ $purchase->plot_area ? number_format((float)$purchase->plot_area, 2) . ' sq ft' : 'N/A' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Plot Price</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ $purchase->plot_price ? number_format((float)$purchase->plot_price, 2) : 'N/A' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                    </div>
                </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Location</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" value="{{ $purchase->location ?: 'N/A' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Installments</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" value="{{ $purchase->installments ?: 'N/A' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Purchase Date</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="text" value="{{ $purchase->date->format('Y-m-d') }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Credit Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Credit Date *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="installment_no" class="block text-sm font-medium text-slate-700 mb-2">Installment Number *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-hashtag"></i></span>
                                <input type="text" wire:model="installment_no" id="installment_no" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="e.g., 1, 2, 3 or First, Second">
                            </div>
                            @error('installment_no') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="installment_amount" class="block text-sm font-medium text-slate-700 mb-2">Installment Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="installment_amount" id="installment_amount" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Total installment amount</p>
                            @error('installment_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="paid_amount" class="block text-sm font-medium text-slate-700 mb-2">Paid Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="paid_amount" id="paid_amount" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Amount paid in this installment</p>
                            @error('paid_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Calculated Values -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Calculated Values</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Remaining Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($remaining, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600 font-semibold" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated: Installment Amount - Paid Amount</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Total Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($total, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-blue-50 text-blue-700 font-semibold" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated: Total installment amount</p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Notes</h2>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Additional Notes</label>
                        <textarea wire:model="notes" id="notes" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Add any additional notes about this credit transaction..."></textarea>
                        @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('property.purchase.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white rounded-lg">Record Credit</button>
                </div>
            </form>
        </div>
    </div>
</div>
