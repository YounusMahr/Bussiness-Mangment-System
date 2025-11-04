<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Car Rent Udhaar</h1>
                <p class="text-gray-600 mt-1">All vehicle booking credit transactions</p>
            </div>
            <div class="mt-2 sm:mt-0 flex-shrink-0">
                <a 
                    wire:navigate
                    href="{{ localized_route('car-rent.udaar.add') }}"
                    class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow-soft-xl"
                >
                    <i class="fas fa-plus"></i> Add Udhaar
                </a>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500"><i class="fas fa-search"></i></span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="Search by customer name..."
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                        <tr>
                            <th wire:click="sortBy('id')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th wire:click="sortBy('date')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Date
                                    @if($sortField === 'date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('customer')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Customer
                                    @if($sortField === 'customer')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                            <th wire:click="sortBy('total_amount')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Total Amount
                                    @if($sortField === 'total_amount')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('paid_amount')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Paid Amount
                                    @if($sortField === 'paid_amount')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('udaar_amount')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Udhaar Amount
                                    @if($sortField === 'udaar_amount')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('status')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Status
                                    @if($sortField === 'status')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('due_date')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    Due Date
                                    @if($sortField === 'due_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($udaars as $udaar)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">
                                    {{ $udaars->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $udaar->date ? $udaar->date->format('M d, Y') : '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $udaar->customer }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($udaar->booking)
                                        <div>
                                            <div class="font-medium">{{ $udaar->booking->vehicle?->Vehicle_name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $udaar->booking->date->format('M d, Y') }}</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rs {{ number_format($udaar->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    Rs {{ number_format($udaar->paid_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                    Rs {{ number_format($udaar->udaar_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($udaar->status === 'paid') bg-green-100 text-green-800
                                        @elseif($udaar->status === 'unpaid') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ Str::ucfirst($udaar->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($udaar->due_date)
                                        <span class="@if($udaar->due_date < now()) text-red-600 font-semibold @elseif($udaar->due_date->diffInDays(now()) <= 7) text-yellow-600 @endif">
                                            {{ $udaar->due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <button title="View" wire:click="viewUdaar({{ $udaar->id }})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                        <a wire:navigate title="Edit" href="{{ localized_localized_route('car-rent.udaar.edit', $udaar) }}" class="text-indigo-600 hover:text-indigo-800"><i class="fas fa-edit"></i></a>
                                        <button title="Delete" wire:click="confirmDelete({{ $udaar->id }})" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-file-invoice-dollar text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Udhaar records found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first Udhaar record.</p>
                                        <a wire:navigate href="{{ localized_route('car-rent.udaar.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                                            Add Udhaar
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
                @forelse($udaars as $udaar)
                    <div class="p-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $udaar->customer }}</h3>
                                <p class="text-sm text-gray-500">{{ $udaar->date ? $udaar->date->format('M d, Y') : '--' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($udaar->status === 'paid') bg-green-100 text-green-800
                                @elseif($udaar->status === 'unpaid') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ Str::ucfirst($udaar->status) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mt-3">
                            @if($udaar->booking)
                                <div class="flex justify-between">
                                    <span>Vehicle:</span>
                                    <span class="font-medium">{{ $udaar->booking->vehicle?->Vehicle_name ?? 'N/A' }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Total Amount:</span>
                                <span class="font-medium">Rs {{ number_format($udaar->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Paid Amount:</span>
                                <span class="font-medium text-green-600">Rs {{ number_format($udaar->paid_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Udhaar Amount:</span>
                                <span class="font-medium text-red-600">Rs {{ number_format($udaar->udaar_amount, 2) }}</span>
                            </div>
                            @if($udaar->due_date)
                                <div class="flex justify-between">
                                    <span>Due Date:</span>
                                    <span class="font-medium @if($udaar->due_date < now()) text-red-600 @elseif($udaar->due_date->diffInDays(now()) <= 7) text-yellow-600 @endif">
                                        {{ $udaar->due_date->format('M d, Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-end space-x-2 mt-3">
                            <button wire:click="viewUdaar({{ $udaar->id }})" class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded">
                                <i class="fas fa-eye mr-1"></i>View
                            </button>
                            <a wire:navigate href="{{ localized_route('car-rent.udaar.edit', $udaar) }}" class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <button wire:click="confirmDelete({{ $udaar->id }})" class="text-red-600 hover:text-red-900 px-3 py-1 rounded">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-file-invoice-dollar text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Udhaar records found</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first Udhaar record.</p>
                        <a wire:navigate href="{{ localized_route('car-rent.udaar.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                            Add Udhaar
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($udaars->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $udaars->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- View Modal -->
    @if($this->viewingUdaar)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-8 relative">
                    <button wire:click="closeView" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Udhaar Details</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Date</label>
                                <p class="text-gray-900">{{ $this->viewingUdaar->date ? $this->viewingUdaar->date->format('M d, Y') : '--' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Customer</label>
                                <p class="text-gray-900 font-medium">{{ $this->viewingUdaar->customer }}</p>
                            </div>
                            @if($this->viewingUdaar->booking)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Vehicle</label>
                                    <p class="text-gray-900">{{ $this->viewingUdaar->booking->vehicle?->Vehicle_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Booking Date</label>
                                    <p class="text-gray-900">{{ $this->viewingUdaar->booking->date->format('M d, Y') }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Amount</label>
                                <p class="text-gray-900 font-semibold">Rs {{ number_format($this->viewingUdaar->total_amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Paid Amount</label>
                                <p class="text-green-600 font-semibold">Rs {{ number_format($this->viewingUdaar->paid_amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Udhaar Amount</label>
                                <p class="text-red-600 font-semibold">Rs {{ number_format($this->viewingUdaar->udaar_amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($this->viewingUdaar->status === 'paid') bg-green-100 text-green-800
                                        @elseif($this->viewingUdaar->status === 'unpaid') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ Str::ucfirst($this->viewingUdaar->status) }}
                                    </span>
                                </p>
                            </div>
                            @if($this->viewingUdaar->due_date)
<div>
                                    <label class="text-sm font-medium text-gray-500">Due Date</label>
                                    <p class="@if($this->viewingUdaar->due_date < now()) text-red-600 font-semibold @elseif($this->viewingUdaar->due_date->diffInDays(now()) <= 7) text-yellow-600 @endif">
                                        {{ $this->viewingUdaar->due_date->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeView" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeleteId)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
                <div class="mb-5">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Delete Udhaar?</h3>
                    <p class="text-slate-600 text-sm">Are you sure you want to delete this Udhaar record? This action cannot be undone.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">Yes, Delete</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
