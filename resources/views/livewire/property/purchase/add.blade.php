<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.add_plot_purchase') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.create_new_plot_purchase_record') }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('property.purchase.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_plot_purchases') }}
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
                                <select wire:model="customer_id" id="customer_id" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="">{{ __('messages.select_customer') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} @if($customer->number) - {{ $customer->number }} @endif</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('customer_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Plot Details -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.plot_details') }}</h2>
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
                            <label for="plot_area" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.plot_area') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-ruler-combined"></i></span>
                                <input type="text" wire:model="plot_area" id="plot_area" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., 5 Marla, 10 Marla">
                            </div>
                            @error('plot_area') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.pricing') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="plot_price" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.plot_price') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">Rs</span>
                                <input type="number" wire:model="plot_price" id="plot_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0.00">
                            </div>
                            @error('plot_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Installments -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.installments') }}</h2>
                    <div>
                        <label for="installments" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.installment_details') }} ({{ __('messages.optional') }})</label>
                        <textarea wire:model="installments" id="installments" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.enter_installment_details_if_any') }}"></textarea>
                        <p class="mt-1 text-xs text-slate-500">{{ __('messages.installment_details_optional') }}</p>
                        @error('installments') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.location') }}</h2>
                    <div>
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.address') }} *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400 top-3"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea wire:model="location" id="location" rows="3" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.enter_plot_address') }}"></textarea>
                        </div>
                        @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('property.purchase.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg">{{ __('messages.save_plot_purchase') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
