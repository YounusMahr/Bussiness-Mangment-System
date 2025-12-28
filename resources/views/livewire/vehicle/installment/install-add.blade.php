<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.add_new_installment') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.add_new_amount_to_existing_installment') }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_installments') }}
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Current Installment Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('messages.current_installment_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.customer_name') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->customer->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.vehicle') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->vehicle ?? '--' }} {{ $installment->model ? '(' . $installment->model . ')' : '' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.current_car_price') }}</label>
                        <p class="text-base font-semibold text-blue-600">Rs {{ number_format($installment->car_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.current_interest') }}</label>
                        <p class="text-base font-semibold text-purple-600">Rs {{ number_format($installment->interest, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.current_total_price') }}</label>
                        <p class="text-base font-semibold text-indigo-600">Rs {{ number_format($installment->total_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.current_paid') }}</label>
                        <p class="text-base font-semibold text-green-600">Rs {{ number_format($installment->paid, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.current_remaining') }}</label>
                        <p class="text-base font-semibold text-red-600">Rs {{ number_format($installment->remaining, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <!-- Card header bar -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- New Installment Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.new_installment_details') }}</h2>
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
                            <label for="new_car_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.new_car_price') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_car_price" id="new_car_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.will_be_added_to_existing') }}</p>
                            @error('new_car_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_interest" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.new_interest') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_interest" id="new_interest" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.will_be_added_to_existing') }}</p>
                            @error('new_interest') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_total_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.new_total_price') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="new_total_price" id="new_total_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.calculated_automatically') }} ({{ __('messages.new_car_price') }} + {{ __('messages.new_interest') }})</p>
                            @error('new_total_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_paid" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.new_paid') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="new_paid" id="new_paid" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.will_be_added_to_existing') }}</p>
                            @error('new_paid') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="new_remaining" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.new_remaining') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="new_remaining" id="new_remaining" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.calculated_automatically') }} ({{ __('messages.current_remaining') }} + {{ __('messages.new_remaining_from_new_amount') }})</p>
                            @error('new_remaining') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="time_period" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.time_period') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-clock"></i></span>
                                <input type="text" wire:model="time_period" id="time_period" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., 3 months, 1 year">
                            </div>
                            @error('time_period') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.due_date') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" wire:model="due_date" id="due_date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('due_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.summary_after_addition') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-medium text-slate-500">{{ __('messages.total_car_price') }}</label>
                            <p class="text-lg font-bold text-blue-600">Rs {{ number_format($current_car_price + $new_car_price, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">{{ __('messages.total_interest') }}</label>
                            <p class="text-lg font-bold text-purple-600">Rs {{ number_format($current_interest + $new_interest, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">{{ __('messages.total_price') }}</label>
                            <p class="text-lg font-bold text-indigo-600">Rs {{ number_format($current_total_price + $new_total_price, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">{{ __('messages.total_paid') }}</label>
                            <p class="text-lg font-bold text-green-600">Rs {{ number_format($current_paid + $new_paid, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">{{ __('messages.total_remaining') }}</label>
                            <p class="text-lg font-bold text-red-600">Rs {{ number_format($new_remaining, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.additional_information') }}</h2>
                    <div>
                        <label for="note" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.notes') }} ({{ __('messages.optional') }})</label>
                        <textarea wire:model="note" id="note" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.add_any_additional_notes') }}"></textarea>
                        @error('note') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white rounded-lg">{{ __('messages.add_new_installment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
