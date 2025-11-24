<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('add_return_payment') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('add_return_payment_for_installment') }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('back_to_installments') }}
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Installment Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('installment_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('customer_name') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->customer->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('phone_number') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->number ?: '--' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('vehicle') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->vehicle ?? '--' }} {{ $installment->model ? '(' . $installment->model . ')' : '' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('current_paid') }}</label>
                        <p class="text-base font-semibold text-green-600">Rs {{ number_format($installment->paid, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('current_remaining') }}</label>
                        <p class="text-base font-semibold text-red-600">Rs {{ number_format($installment->remaining, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <!-- Card header bar -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Return Payment Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('return_payment_details') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">{{ __('date') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="total_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('total_price') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="total_price" id="total_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('from_installment') }}</p>
                            @error('total_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="return_payment" class="block text-sm font-medium text-slate-700 mb-2">{{ __('return_payment') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="return_payment" id="return_payment" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('return_payment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="installment_number" class="block text-sm font-medium text-slate-700 mb-2">{{ __('installment_number') }} ({{ __('optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-hashtag"></i></span>
                                <input type="text" wire:model="installment_number" id="installment_number" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., 1, 2, 3">
                            </div>
                            @error('installment_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="remaining" class="block text-sm font-medium text-slate-700 mb-2">{{ __('remaining') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="remaining" id="remaining" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('calculated_automatically') }}</p>
                            @error('remaining') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">{{ __('status') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-info-circle"></i></span>
                                <select wire:model="status" id="status" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="pending">{{ __('pending') }}</option>
                                    <option value="paid">{{ __('paid') }}</option>
                                </select>
                            </div>
                            @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('additional_information') }}</h2>
                    <div>
                        <label for="note" class="block text-sm font-medium text-slate-700 mb-2">{{ __('notes') }} ({{ __('optional') }})</label>
                        <textarea wire:model="note" id="note" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('add_any_additional_notes') }}"></textarea>
                        @error('note') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white rounded-lg">{{ __('save_return_payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
