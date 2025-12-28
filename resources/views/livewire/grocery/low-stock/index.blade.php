<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.low_stock_products') }}</h1>
                </div>
                <div class="flex justify-between w-full">
                    <p class="text-gray-600 mt-1">{{ __('messages.products_with_low_or_empty_stock') }}</p>
                    <a 
                        wire:navigate
                        href="{{ localized_route('products.add') }}" 
                        class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white md:font-bold font-normal py-1 px-2 md:py-2 md:px-2 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-plus"></i>
                        {{ __('messages.add_product') }}
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
                            placeholder="{{ __('messages.search_products') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-full leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
                <div>
                    <button 
                        style="background-color:green;"
                        wire:click="printTable" 
                        class="bg-green-200 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-print"></i>
                        {{ __('messages.print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                        <tr>
                            <th wire:click="sortBy('name')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    {{ __('messages.product_name') }}
                                    @if($sortField === 'name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('sku')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    {{ __('messages.sku') }}
                                    @if($sortField === 'sku')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.category') }}
                            </th>
                            <th wire:click="sortBy('quantity')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    {{ __('messages.quantity') }}
                                    @if($sortField === 'quantity')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('price')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    {{ __('messages.price') }}
                                    @if($sortField === 'price')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.status') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($product->image)
                                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded shadow-md object-cover">
                                        @else
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-gray-200 rounded shadow-md">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            @if($product->description)
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->sku }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium {{ $product->quantity > 10 ? 'text-green-600' : ($product->quantity > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $product->quantity }}
                                        </span>
                                        @if($product->quantity <= 10 && $product->quantity > 0)
                                            <i class="fas fa-exclamation-triangle text-yellow-500 ml-2"></i>
                                        @elseif($product->quantity == 0)
                                            <i class="fas fa-times-circle text-red-500 ml-2"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rs {{ number_format($product->price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ $product->is_active ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a
                                            wire:navigate
                                            href="{{ localized_route('products.edit', $product) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                            title="{{ __('messages.edit_product') }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.no_low_stock_products') }}</h3>
                                        <p class="text-gray-500">{{ __('messages.all_products_have_sufficient_stock') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden">
                @forelse($products as $product)
                    <div class="p-4 border-b border-gray-200 last:border-b-0">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->quantity }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>{{ __('messages.sku') }}:</span>
                                <span class="font-medium">{{ $product->sku }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('messages.category') }}:</span>
                                @if($product->category)
                                    <span class="font-medium text-purple-700">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-gray-400">&mdash;</span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('messages.quantity') }}:</span>
                                <span class="font-medium {{ $product->quantity > 10 ? 'text-green-600' : ($product->quantity > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $product->quantity }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('messages.price') }}:</span>
                                <span class="font-medium">Rs {{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 mt-3">
                            <a 
                                wire:navigate
                                href="{{ localized_route('products.edit', $product) }}"
                                class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded"
                            >
                                <i class="fas fa-edit mr-1"></i>{{ __('messages.edit') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.no_low_stock_products') }}</h3>
                        <p class="text-gray-500">{{ __('messages.all_products_have_sufficient_stock') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Print Styles -->
    <style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            font-size: 12px;
        }
        
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        th, td {
            border: 1px solid #000 !important;
            padding: 8px !important;
        }
    }
    </style>

    <!-- Print JavaScript -->
    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-table', () => {
            window.print();
        });
    });
    </script>
</div>

