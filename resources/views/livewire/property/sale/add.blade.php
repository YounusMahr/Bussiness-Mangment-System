<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.add_plot_sale') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.create_new_plot_sale_record') }}</p>
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

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <!-- Card header bar -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Sale Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.sale_details') }}</h2>
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
                            <label for="plot_purchase_id" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.plot') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-map-marked-alt"></i></span>
                                <select wire:model="plot_purchase_id" id="plot_purchase_id" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="">{{ __('messages.select_plot') }}</option>
                                    @foreach($plotPurchases as $plot)
                                        <option value="{{ $plot->id }}">{{ $plot->plot_area }} - {{ $plot->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('plot_purchase_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.customer_information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="customer_id" class="block text-sm font-medium text-slate-700">{{ __('messages.customer') }} ({{ __('messages.optional') }})</label>
                                <a
                                    wire:navigate
                                    href="{{ localized_route('property.customer.add') }}"
                                    class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1"
                                >
                                    <i class="fas fa-plus text-xs"></i>
                                    {{ __('messages.add_customer') }}
                                </a>
                            </div>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <select wire:model.live="customer_id" id="customer_id" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="">{{ __('messages.select_customer') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} @if($customer->number) - {{ $customer->number }} @endif</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($customer_id)
                                <p class="mt-1 text-xs text-green-600 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i>
                                    {{ __('messages.customer_info_auto_filled') ?? 'Customer information will be auto-filled below' }}
                                </p>
                            @endif
                            @error('customer_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.customer_name') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <input type="text" wire:model="customer_name" id="customer_name" 
                                    class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent {{ $customer_id ? 'bg-gray-50 cursor-not-allowed' : '' }}" 
                                    placeholder="{{ __('messages.enter_customer_name') }}"
                                    @if($customer_id) readonly @endif>
                            </div>
                            @if($customer_id)
                                <p class="mt-1 text-xs text-slate-500">{{ __('messages.auto_filled_from_customer') ?? 'Auto-filled from selected customer' }}</p>
                            @endif
                            @error('customer_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="customer_number" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.customer_number') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-phone"></i></span>
                                <input type="text" wire:model="customer_number" id="customer_number" 
                                    class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent {{ $customer_id ? 'bg-gray-50 cursor-not-allowed' : '' }}" 
                                    placeholder="{{ __('messages.enter_customer_number') }}"
                                    @if($customer_id) readonly @endif>
                            </div>
                            @if($customer_id)
                                <p class="mt-1 text-xs text-slate-500">{{ __('messages.auto_filled_from_customer') ?? 'Auto-filled from selected customer' }}</p>
                            @endif
                            @error('customer_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Financial Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.financial_details') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="total_sale_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.total_sale_price') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="total_sale_price" id="total_sale_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('total_sale_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="paid" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.paid') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="paid" id="paid" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('paid') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="interest" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.interest') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="interest" id="interest" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('interest') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="remaining" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.remaining') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="remaining" id="remaining" step="0.01" min="0" readonly class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-gray-50 cursor-not-allowed" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.auto_calculated') }}</p>
                            @error('remaining') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.additional_details') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="time_period" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.time_period') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-clock"></i></span>
                                <input type="text" wire:model="time_period" id="time_period" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="e.g., 6 months, 1 year">
                            </div>
                            @error('time_period') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.status') }} *</label>
                            <select wire:model="status" id="status" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                <option value="remaining">{{ __('messages.remaining') }}</option>
                                <option value="paid">{{ __('messages.paid') }}</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Installment Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Installment Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="installment_no" class="block text-sm font-medium text-slate-700 mb-2">Installment Number (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-hashtag"></i></span>
                                <input type="text" wire:model.live="installment_no" id="installment_no" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="e.g., 1, 2, 3 or First, Second">
                            </div>
                            @error('installment_no') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="installment_amount" class="block text-sm font-medium text-slate-700 mb-2">Installment Amount (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="installment_amount" id="installment_amount" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Total installment amount</p>
                            @error('installment_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="paid_amount" class="block text-sm font-medium text-slate-700 mb-2">Paid Amount (Optional)</label>
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
                                <input type="text" value="{{ number_format($remaining_calc, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600 font-semibold" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated: Installment Amount - Paid Amount</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Total Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="text" value="{{ number_format($total_calc, 2) }}" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-blue-50 text-blue-700 font-semibold" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Auto-calculated: Total installment amount</p>
                        </div>
                    </div>
                </div>

                <!-- Installment Details (Optional) -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.installments') }}</h2>
                    <div>
                        <label for="installments" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.installment_details') }} ({{ __('messages.optional') }})</label>
                        <textarea wire:model="installments" id="installments" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.enter_installment_details_if_any') }}"></textarea>
                        <p class="mt-1 text-xs text-slate-500">{{ __('messages.installment_details_optional') }}</p>
                        @error('installments') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('property.sale.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg">{{ __('messages.save_plot_sale') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
