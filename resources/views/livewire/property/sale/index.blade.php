<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('plot_sales') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('manage_plot_sale_records') }}</p>
            </div>
            <div>
                <a 
                    wire:navigate
                    href="{{ localized_route('property.sale.add') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-medium rounded-lg shadow-md transition-colors"
                >
                    <i class="fas fa-plus"></i>
                    {{ __('add_plot_sale') }}
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
                            placeholder="{{ __('search_plot_sales') }}"
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
                            <th wire:click="sortBy('date')" class="px-6 py-4 cursor-pointer text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    {{ __('date') }}
                                    @if($sortField === 'date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('plot') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('customer_name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('customer_number') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('total_sale_price') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('paid') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('remaining') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $sale->date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->plotPurchase->plot_area ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($sale->plotPurchase->location ?? '--', 30) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $sale->customer_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $sale->customer_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                    Rs {{ number_format($sale->total_sale_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    Rs {{ number_format($sale->paid, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                    Rs {{ number_format($sale->remaining, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sale->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a
                                            wire:navigate
                                            href="{{ localized_route('property.sale.edit', $sale) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                            title="{{ __('edit') }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button 
                                            wire:click="confirmDelete({{ $sale->id }})"
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            title="{{ __('delete') }}"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-handshake text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('no_plot_sales_found') }}</h3>
                                        <p class="text-gray-500 mb-4">{{ __('get_started_by_creating_first_plot_sale') }}</p>
                                        <a wire:navigate href="{{ localized_route('property.sale.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                                            {{ __('add_plot_sale') }}
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
                @forelse($sales as $sale)
                    <div class="p-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $sale->customer_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $sale->customer_number }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sale->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mb-3">
                            <div class="flex justify-between">
                                <span>{{ __('date') }}:</span>
                                <span class="font-medium">{{ $sale->date->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('plot') }}:</span>
                                <span class="font-medium">{{ $sale->plotPurchase->plot_area ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('total_sale_price') }}:</span>
                                <span class="font-medium text-blue-600">Rs {{ number_format($sale->total_sale_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('paid') }}:</span>
                                <span class="font-medium text-green-600">Rs {{ number_format($sale->paid, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('remaining') }}:</span>
                                <span class="font-medium text-red-600">Rs {{ number_format($sale->remaining, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <a 
                                wire:navigate
                                href="{{ localized_route('property.sale.edit', $sale) }}"
                                class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded"
                            >
                                <i class="fas fa-edit mr-1"></i>{{ __('edit') }}
                            </a>
                            <button 
                                wire:click="confirmDelete({{ $sale->id }})"
                                class="text-red-600 hover:text-red-900 px-3 py-1 rounded"
                            >
                                <i class="fas fa-trash mr-1"></i>{{ __('delete') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-handshake text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('no_plot_sales_found') }}</h3>
                        <p class="text-gray-500 mb-4">{{ __('get_started_by_creating_first_plot_sale') }}</p>
                        <a wire:navigate href="{{ localized_route('property.sale.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                            {{ __('add_plot_sale') }}
                        </a>
                    </div>
                @endforelse
            </div>

            @if($sales->hasPages())
                <div class="flex justify-center py-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-b-2xl shadow-inner mt-2">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeleteId)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
                <div class="mb-5">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('delete_plot_sale') }}?</h3>
                    <p class="text-slate-600 text-sm">{{ __('are_you_sure_delete_plot_sale') }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">{{ __('yes_delete') }}</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">{{ __('cancel') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
