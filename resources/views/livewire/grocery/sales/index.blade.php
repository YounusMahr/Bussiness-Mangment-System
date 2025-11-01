<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center">
<div>
                <h1 class="text-2xl font-bold text-gray-900">Sales</h1>
                <p class="text-gray-600 mt-1">All recent POS transactions</p>
            </div>
            <div class="mt-2 sm:mt-0 flex-shrink-0">
                <a 
                    wire:navigate
                    href="{{ route('sales.add') }}"
                    class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow-soft-xl"
                >
                    <i class="fas fa-plus"></i> New Sale
                </a>
            </div>
        </div>

        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500"><i class="fas fa-search"></i></span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="Search sales by customer or notes..."
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
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">
                                    {{ $sales->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                        $firstItem = $sale->saleItems->first();
                                        $firstName = $firstItem?->product?->name ?? '--';
                                        $more = max($sale->saleItems->count() - 1, 0);
                                    @endphp
                                    {{ $firstName }} @if($more > 0)<span class="text-gray-500 text-xs">+{{ $more }} more</span>@endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $sale->saleItems->sum('quantity') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">${{ number_format($sale->paid_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($sale->status === 'paid') bg-green-100 text-green-800
                                        @elseif($sale->status === 'unpaid') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ Str::ucfirst($sale->status ?: 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <a wire:navigate title="Edit" href="{{ route('sales.edit', $sale) }}" class="text-indigo-600 hover:text-indigo-800"><i class="fas fa-edit"></i></a>
                                        <button title="Delete" class="text-red-600 hover:text-red-800" onclick="if(!confirm('Delete this sale?')) return false;" wire:click="deleteSale({{ $sale->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
                <div class="flex justify-center py-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-b-2xl shadow-inner mt-2">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
