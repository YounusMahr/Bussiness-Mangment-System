<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.udaar') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('messages.all_credit_transactions') }}</p>
            </div>
            <div class="mt-2 sm:mt-0 flex-shrink-0">
                <a 
                    wire:navigate
                    href="{{ localized_route('udaar.add') }}"
                    class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow-soft-xl"
                >
                    <i class="fas fa-plus"></i> {{ __('messages.add_udaar') }}
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
                            placeholder="Search by customer name, number or notes..."
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
                            <th wire:click="sortBy('buy_date')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th wire:click="sortBy('customer_name')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interest</th>
                            <th wire:click="sortBy('due_date')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
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
                                    {{ $udaar->buy_date ? $udaar->buy_date->format('Y-m-d') : '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $udaar->customer_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $udaar->customer_number ?: '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                    Rs {{ number_format($udaar->paid_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                    Rs {{ number_format($udaar->remaining_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-orange-600">
                                    Rs {{ number_format($udaar->interest_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($udaar->due_date)
                                        <span class="@if($udaar->due_date < now()) text-red-600 font-semibold @elseif($udaar->due_date->diffInDays(now()) <= 7) text-yellow-600 @endif">
                                            {{ $udaar->due_date->format('Y-m-d') }}
                                    </span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <button title="{{ __('messages.view') }}" wire:click="viewUdaar({{ $udaar->id }})" class="text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                        <a wire:navigate title="{{ __('messages.edit') }}" href="{{ localized_route('udaar.edit', $udaar) }}" class="text-indigo-600 hover:text-indigo-800"><i class="fas fa-edit"></i></a>
                                        <button title="{{ __('messages.delete') }}" class="text-red-600 hover:text-red-800" onclick="if(!confirm('{{ __('messages.delete_udaar_record') }}')) return false;" wire:click="deleteUdaar({{ $udaar->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-6 py-12 text-center text-gray-500">{{ __('messages.no_udaar_records_found') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($udaars->hasPages())
                <div class="flex justify-center py-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-b-2xl shadow-inner mt-2">
                    {{ $udaars->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- View Modal -->
    @if($this->viewingUdaar)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Background overlay (no darkening, only blur) -->
            <div class="fixed inset-0 bg-transparent backdrop-blur-sm" wire:click="closeView"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-700 to-pink-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Udhaar Details</h3>
                            <button wire:click="closeView" class="text-white hover:text-gray-200 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Information -->
                            <div class="md:col-span-2">
                                <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                                    <i class="fas fa-user-circle mr-2 text-purple-600"></i>
                                    Customer Information
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">Name:</span>
                                        <span class="text-sm text-gray-900 font-medium">{{ $this->viewingUdaar->customer_name }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">Number:</span>
                                        <span class="text-sm text-gray-900">{{ $this->viewingUdaar->customer_number ?: '--' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Dates -->
                            <div>
                                <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                                    Dates
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">Buy Date:</span>
                                        <span class="text-sm text-gray-900">{{ $this->viewingUdaar->buy_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">Due Date:</span>
                                        @if($this->viewingUdaar->due_date)
                                            <span class="text-sm font-medium @if($this->viewingUdaar->due_date < now()) text-red-600 @elseif($this->viewingUdaar->due_date->diffInDays(now()) <= 7) text-yellow-600 @else text-gray-900 @endif">
                                                {{ $this->viewingUdaar->due_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">Not set</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Amount Details -->
                            <div>
                                <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                                    <i class="fas fa-dollar-sign mr-2 text-purple-600"></i>
                                    {{ __('messages.amounts') }}
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">{{ __('messages.total') }}:</span>
                                        <span class="text-sm font-bold text-gray-900">Rs {{ number_format($this->viewingUdaar->paid_amount + $this->viewingUdaar->remaining_amount - $this->viewingUdaar->interest_amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">{{ __('messages.paid') }}:</span>
                                        <span class="text-sm font-bold text-blue-600">Rs {{ number_format($this->viewingUdaar->paid_amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">{{ __('messages.remaining') }}:</span>
                                        <span class="text-sm font-bold text-red-600">Rs {{ number_format($this->viewingUdaar->remaining_amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-sm font-medium text-gray-500 w-32">{{ __('messages.interest') }}:</span>
                                        <span class="text-sm font-bold text-orange-600">Rs {{ number_format($this->viewingUdaar->interest_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($this->viewingUdaar->notes)
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4 flex items-center">
                                        <i class="fas fa-sticky-note mr-2 text-purple-600"></i>
                                        Notes
                                    </h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $this->viewingUdaar->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button wire:click="closeView" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                            {{ __('messages.close') }}
                        </button>
                        <a wire:navigate href="{{ localized_route('udaar.edit', $this->viewingUdaar) }}" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-medium rounded-lg transition-all">
                            <i class="fas fa-edit mr-2"></i> {{ __('messages.edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
