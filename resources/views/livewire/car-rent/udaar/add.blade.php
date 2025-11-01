<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Add New Udhaar</h1>
                    <p class="text-slate-600 mt-1">Record a credit transaction for vehicle booking</p>
                </div>
                <a wire:navigate href="{{ route('car-rent.udaar.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    Back to Udhaar
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
                <!-- Booking Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Booking Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                            <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Date *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar"></i></span>
                                <input type="date" wire:model="date" id="date" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                        <div>
                            <label for="booking_id" class="block text-sm font-medium text-slate-700 mb-2">Booking (Optional)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-calendar-check"></i></span>
                                <select wire:model="booking_id" id="booking_id" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="">Select Booking</option>
                                    @foreach($bookings as $booking)
                                        <option value="{{ $booking->id }}">
                                            {{ $booking->customer_name }} - {{ $booking->vehicle?->Vehicle_name ?? 'N/A' }} ({{ $booking->date->format('M d, Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                                </div>
                            @error('booking_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Selecting a booking will auto-fill customer and total amount</p>
            </div>
                </div>
                </div>

                <!-- Customer and Amount Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Customer & Amount Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer" class="block text-sm font-medium text-slate-700 mb-2">Customer *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <input type="text" wire:model="customer" id="customer" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400 {{ $booking_id ? 'bg-gray-50' : '' }}" placeholder="Enter customer name" {{ $booking_id ? 'readonly' : '' }}>
                            </div>
                            @error('customer') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            @if($booking_id)
                                <p class="text-xs text-gray-500 mt-1">Auto-filled from booking</p>
                            @endif
                        </div>
                <div>
                            <label for="total_amount" class="block text-sm font-medium text-slate-700 mb-2">Total Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" step="0.01" wire:model="total_amount" id="total_amount" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400 {{ $booking_id ? 'bg-gray-50' : '' }}" placeholder="0.00" {{ $booking_id ? 'readonly' : '' }}>
                </div>
                            @error('total_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            @if($booking_id)
                                <p class="text-xs text-gray-500 mt-1">Auto-filled from booking</p>
                            @endif
            </div>
                <div>
                            <label for="paid_amount" class="block text-sm font-medium text-slate-700 mb-2">Paid Amount *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" step="0.01" wire:model="paid_amount" id="paid_amount" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="0.00">
                            </div>
                            @error('paid_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                            <label for="udaar_amount" class="block text-sm font-medium text-slate-700 mb-2">Udhaar Amount</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-file-invoice-dollar"></i></span>
                                <input type="number" step="0.01" wire:model="udaar_amount" id="udaar_amount" readonly class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 cursor-not-allowed" placeholder="Auto-calculated">
                            </div>
                            @error('udaar_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Auto-calculated (Total - Paid)</p>
                        </div>
                    </div>
                </div>

                <!-- Status and Due Date -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Status & Due Date</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Status *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-info-circle"></i></span>
                                <select wire:model="status" id="status" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                </select>
                            </div>
                            @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Auto-updates based on payment</p>
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

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <a wire:navigate href="{{ route('car-rent.udaar.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-medium rounded-lg transition-all shadow-soft-xl">
                        <i class="fas fa-save mr-2"></i>
                        Save Udhaar
                    </button>
                </div>
            </form>
            </div>
    </div>
</div>
