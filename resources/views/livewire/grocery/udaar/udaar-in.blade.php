<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Add New Udaar</h1>
                    <p class="text-slate-600 mt-1">Add new udaar purchase to existing record</p>
                </div>
                <a wire:navigate href="{{ localized_route('udaar.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    Back to Udaar
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
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Customer Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Customer Name</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <input type="text" value="{{ $udaar->customer_name }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Customer Number</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-phone"></i></span>
                                <input type="text" value="{{ $udaar->customer_number ?: '--' }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Current Status</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Paid Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($current_paid, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                @if($current_remaining < 0)
                                    Credit Balance
                                @else
                                    Current Remaining Amount
                                @endif
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                @if($current_remaining < 0)
                                    <input type="text" value="{{ number_format(abs($current_remaining), 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-blue-50 text-blue-700 font-semibold" readonly>
                                    <p class="mt-1 text-xs text-blue-600">This credit will be applied to new purchases</p>
                                @else
                                <input type="text" value="{{ number_format($current_remaining, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Udaar Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">New Udaar Purchase Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Purchase Date *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="product_id" class="block text-sm font-medium text-slate-700">Product</label>
                                <a 
                                    wire:navigate
                                    href="{{ localized_route('products.add') }}"
                                    class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1"
                                >
                                    <i class="fas fa-plus text-xs"></i>
                                    Add New Product
                                </a>
                            </div>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-box"></i></span>
                                <select wire:model="product_id" id="product_id" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="">Select Product (Optional)</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('product_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_udaar_amount" class="block text-sm font-medium text-slate-700 mb-2">New Udaar Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_udaar_amount" id="new_udaar_amount" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Amount of new purchase on udaar</p>
                            @error('new_udaar_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="interest_amount" class="block text-sm font-medium text-slate-700 mb-2">Interest Amount (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="interest_amount" id="interest_amount" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('interest_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="time_period" class="block text-sm font-medium text-slate-700 mb-2">Time Period (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-clock"></i></span>
                                <input type="text" wire:model="time_period" id="time_period" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., 3 months, 1 year">
                            </div>
                            @error('time_period') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700 mb-2">Due Date (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" wire:model="due_date" id="due_date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('due_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- New Status -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">New Status (After Adding Udaar)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Paid Amount (Unchanged)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($current_paid, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">New Remaining Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($new_remaining, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-red-50 text-red-700 font-semibold" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Current remaining + new udaar amount</p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Additional Information</h2>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute top-3 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-sticky-note"></i></span>
                            <textarea wire:model="notes" id="notes" rows="4" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Add any additional notes..."></textarea>
                        </div>
                        @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <a wire:navigate href="{{ localized_route('udaar.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white font-medium rounded-lg transition-all shadow-soft-xl">
                        <i class="fas fa-save mr-2"></i>
                        Add New Udaar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
