<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Process Stock Out</h1>
                    <p class="text-slate-600 mt-1">Return stock and process payment</p>
                </div>
                <a wire:navigate href="{{ localized_route('purchases.bulk') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
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
            <div class="bg-gradient-to-r from-red-600 to-pink-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Seller Information (Read-only) -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Seller Information</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Seller Name</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <input type="text" value="{{ $purchase->seller_name }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Contact</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-phone"></i></span>
                                <input type="text" value="{{ $purchase->contact ?: '--' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Current Status</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Remaining Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-layer-group"></i></span>
                                <input type="text" value="{{ number_format($current_remaining_stock, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Maximum returnable: {{ number_format($current_remaining_stock, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Total Remaining Payment</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($current_total_remaining, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Maximum payable: Rs {{ number_format($current_total_remaining, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Return Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Return Details</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Return Date *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="return_stock" class="block text-sm font-medium text-slate-700 mb-2">Return Stock Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-box"></i></span>
                                <input type="number" wire:model.live="return_stock" id="return_stock" step="0.01" min="0" max="{{ $current_remaining_stock }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Cannot exceed remaining stock</p>
                            @error('return_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="return_payment" class="block text-sm font-medium text-slate-700 mb-2">Return Payment Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="return_payment" id="return_payment" step="0.01" min="0" max="{{ $current_total_remaining }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Cannot exceed remaining payment</p>
                            @error('return_payment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- New Status After Return -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">New Status (After Return)</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Remaining Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-layer-group"></i></span>
                                <input type="text" value="{{ number_format($new_remaining_stock, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-green-50 text-green-700 font-semibold" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Total Remaining Payment</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($new_total_remaining, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-red-50 text-red-700 font-semibold" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Paid Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($new_paid, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-blue-50 text-blue-700 font-semibold" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Notes</h2>
                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Additional Notes</label>
                        <textarea wire:model="notes" id="notes" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Add any additional notes..."></textarea>
                        @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('purchases.bulk') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-600 text-white rounded-lg">Process Stock Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
