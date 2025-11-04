<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Vehicle Bookings</h1>
                    <p class="text-gray-600 mt-1">Manage vehicle bookings</p>
                </div>
                <div class="mt-2 sm:mt-0 flex-shrink-0">
                    <a 
                        wire:navigate
                        href="{{ localized_route('bookings.add') }}" 
                        class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white md:font-bold font-normal py-1 px-2 md:py-2 md:px-2 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-plus"></i>
                        Add Booking
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                            <i class="fas fa-search"></i>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="Search bookings..."
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-full leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                        <tr>
                            <th wire:click="sortBy('date')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Date
                                    @if($sortField === 'date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th wire:click="sortBy('rent_days')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Days
                                    @if($sortField === 'rent_days')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('return_date')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Return Date
                                    @if($sortField === 'return_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Remaining Days
                            </th>
                            <th wire:click="sortBy('total_price')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    Total Price
                                    @if($sortField === 'total_price')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                            @php
                                $remainingDays = $this->getRemainingDays($booking->return_date);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @if($booking->vehicle)
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->vehicle->Vehicle_name }}</div>
                                                @if($booking->vehicle->model)
                                                    <div class="text-xs text-gray-500">{{ $booking->vehicle->model }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</div>
                                        @if($booking->customer_number)
                                            <div class="text-xs text-gray-500">{{ $booking->customer_number }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $booking->rent_days }} day(s)</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $booking->return_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($remainingDays > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $remainingDays }} day(s) left
                                        </span>
                                    @elseif($remainingDays == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Due today
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Overdue ({{ abs($remainingDays) }} day(s))
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rs {{ number_format($booking->total_price, 2) }}</div>
                                    <div class="text-xs text-gray-500">Rs {{ number_format($booking->price, 2) }}/day</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2 space-x-2">
                                        <a
                                            wire:navigate
                                            href="{{ localized_route('bookings.edit', $booking) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                            title="Edit Booking"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button 
                                            wire:click="confirmDelete({{ $booking->id }})"
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            title="Delete Booking"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-check text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first booking.</p>
                                        <a wire:navigate href="{{ localized_route('bookings.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                                            Add Booking
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden">
                @forelse($bookings as $booking)
                    @php
                        $remainingDays = $this->getRemainingDays($booking->return_date);
                    @endphp
                    <div class="p-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex justify-between items-start mb-2">
<div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $booking->customer_name }}</h3>
                                @if($booking->customer_number)
                                    <p class="text-sm text-gray-500">{{ $booking->customer_number }}</p>
                                @endif
                            </div>
                            @if($remainingDays > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $remainingDays }} day(s) left
                                </span>
                            @elseif($remainingDays == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Due today
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Overdue
                                </span>
                            @endif
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mt-3">
                            <div class="flex justify-between">
                                <span>Vehicle:</span>
                                <span class="font-medium text-purple-700">
                                    {{ $booking->vehicle?->Vehicle_name ?? 'N/A' }}
                                    @if($booking->vehicle?->model)
                                        ({{ $booking->vehicle->model }})
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>Booking Date:</span>
                                <span class="font-medium">{{ $booking->date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Return Date:</span>
                                <span class="font-medium">{{ $booking->return_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Rent Days:</span>
                                <span class="font-medium">{{ $booking->rent_days }} day(s)</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Price:</span>
                                <span class="font-medium">Rs {{ number_format($booking->total_price, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 mt-3">
                            <a 
                                wire:navigate
                                href="{{ localized_route('bookings.edit', $booking) }}"
                                class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded"
                            >
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <button 
                                wire:click="confirmDelete({{ $booking->id }})"
                                class="text-red-600 hover:text-red-900 px-3 py-1 rounded"
                            >
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-calendar-check text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first booking.</p>
                        <a wire:navigate href="{{ localized_route('bookings.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                            Add Booking
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Confirmation Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
                <div class="mb-5">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Delete Booking?</h3>
                    <p class="text-slate-600 text-sm">Are you sure you want to delete this booking? The vehicle will be marked as available.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">Yes, Delete</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
