<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Add Stock In</h1>
                    <p class="text-slate-600 mt-1">Add new stock to existing purchase</p>
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
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>

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
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Total Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-layer-group"></i></span>
                                <input type="text" value="{{ number_format($current_total_stock, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Remaining Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calculator"></i></span>
                                <input type="text" value="{{ number_format($current_remaining_stock, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Total Remaining Payment</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($current_total_remaining, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Stock In Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">New Stock In Details</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Date *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_goods_name" class="block text-sm font-medium text-slate-700 mb-2">Additional Goods Name (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-box"></i></span>
                                <input type="text" wire:model="new_goods_name" id="new_goods_name" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Enter additional goods name">
                            </div>
                            @error('new_goods_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- New Financial Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">New Financial Details</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_goods_total_price" class="block text-sm font-medium text-slate-700 mb-2">New Goods Total Price *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_goods_total_price" id="new_goods_total_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('new_goods_total_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_paid" class="block text-sm font-medium text-slate-700 mb-2">New Paid Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_paid" id="new_paid" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('new_paid') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_remaining" class="block text-sm font-medium text-slate-700 mb-2">New Remaining</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="new_remaining" id="new_remaining" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated</p>
                        </div>
                        <div>
                            <label for="new_interest" class="block text-sm font-medium text-slate-700 mb-2">New Interest (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_interest" id="new_interest" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('new_interest') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_total_remaining" class="block text-sm font-medium text-slate-700 mb-2">New Total Remaining</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="new_total_remaining" id="new_total_remaining" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated</p>
                        </div>
                    </div>
                </div>

                <!-- New Stock Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">New Stock Details</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_total_stock" class="block text-sm font-medium text-slate-700 mb-2">New Total Stock *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-layer-group"></i></span>
                                <input type="number" wire:model.live="new_total_stock" id="new_total_stock" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('new_total_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_given_stock" class="block text-sm font-medium text-slate-700 mb-2">New Given Stock *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-check-circle"></i></span>
                                <input type="number" wire:model.live="new_given_stock" id="new_given_stock" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('new_given_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_remaining_stock" class="block text-sm font-medium text-slate-700 mb-2">New Remaining Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calculator"></i></span>
                                <input type="number" wire:model="new_remaining_stock" id="new_remaining_stock" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated</p>
                        </div>
                    </div>
                </div>

                <!-- New Status After Stock In -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">New Status (After Stock In)</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Total Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-layer-group"></i></span>
                                <input type="text" value="{{ number_format($new_total_stock_final, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-green-50 text-green-700 font-semibold" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Remaining Stock</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calculator"></i></span>
                                <input type="text" value="{{ number_format($new_remaining_stock_final, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-green-50 text-green-700 font-semibold" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Total Remaining Payment</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($new_total_remaining_final, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-red-50 text-red-700 font-semibold" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Additional Information</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="time_period" class="block text-sm font-medium text-slate-700 mb-2">Time Period</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-clock"></i></span>
                                <input type="text" wire:model="time_period" id="time_period" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., 3 months, 1 year">
                            </div>
                            @error('time_period') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700 mb-2">Due Date</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" wire:model="due_date" id="due_date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('due_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white rounded-lg">Add Stock In</button>
                </div>
            </form>
        </div>
    </div>
</div>
