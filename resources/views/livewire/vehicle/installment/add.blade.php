<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.add_installment') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.add_new_installment_record') }}</p>
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

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <!-- Card header bar -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.customer_information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.customer') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <select wire:model.live="customer_id" id="customer_id" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="">{{ __('messages.select_customer') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('customer_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="customer_number" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.phone_number') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-phone"></i></span>
                                <input type="text" wire:model="customer_number" id="customer_number" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.auto_filled_from_customer') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.vehicle_information') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="vehicle" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.vehicle') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-car"></i></span>
                                <input type="text" wire:model="vehicle" id="vehicle" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., Toyota, Honda">
                            </div>
                            @error('vehicle') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="model" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.model') }} ({{ __('messages.optional') }})</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-car-side"></i></span>
                                <input type="text" wire:model="model" id="model" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., Corolla, Civic">
                            </div>
                            @error('model') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Installment Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.installment_details') }}</h2>
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
                            <label for="car_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.car_price') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model.live="car_price" id="car_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('car_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                            <label for="total_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.total_price') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="total_price" id="total_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.calculated_automatically') }} ({{ __('messages.car_price') }} + {{ __('messages.interest') }})</p>
                            @error('total_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                            <label for="remaining" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.remaining') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="remaining" id="remaining" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.calculated_automatically') }} ({{ __('messages.total_price') }} - {{ __('messages.paid') }})</p>
                            @error('remaining') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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

                <!-- Additional Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.additional_information') }}</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="installment" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.installment_details') }} ({{ __('messages.optional') }})</label>
                            <textarea wire:model="installment" id="installment" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.enter_installment_details') }}"></textarea>
                            @error('installment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="note" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.notes') }} ({{ __('messages.optional') }})</label>
                            <textarea wire:model="note" id="note" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.add_any_additional_notes') }}"></textarea>
                            @error('note') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-700 hover:to-indigo-600 text-white rounded-lg">{{ __('messages.save_installment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
