<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center no-print">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.sales_management') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('messages.all_recent_pos_transactions') }}</p>
            </div>
            <div class="mt-2 sm:mt-0 flex-shrink-0">
                <a 
                    wire:navigate
                    href="{{ localized_route('sales.add') }}"
                    class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 shadow-soft-xl"
                >
                    <i class="fas fa-plus"></i>{{ __('messages.sale_account') }}
                </a>
            </div>
        </div>

        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6 no-print">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500"><i class="fas fa-search"></i></span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="{{ __('messages.search_sales_by_customer_or_notes') }}..."
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
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

        <!-- Print Table (Hidden on screen, shown when printing) -->
        <div class="hidden print-only">
            <div class="print-header">
                <h1>{{ __('messages.sales_management') }}</h1>
                <p>{{ __('messages.all_recent_pos_transactions') }}</p>
                <p style="font-size: 10px; margin-top: 5px;">{{ __('messages.date') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>
            <table class="print-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.customer_name') }}</th>
                        <th>{{ __('messages.paid_amount') }}</th>
                        <th>{{ __('messages.total_items') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $index => $sale)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sale->customer_name ?: __('messages.walk_in_customer') }}</td>
                            <td>Rs {{ number_format($sale->paid_amount, 2) }}</td>
                            <td>{{ $sale->saleItems->sum('quantity') }}</td>
                            <td>{{ $sale->date->format('M d, Y') }}</td>
                            <td>{{ Str::ucfirst($sale->status ?: 'pending') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 no-print">
            @forelse($sales as $sale)
                @php
                    $customer = $sale->customer_name ? ($customers[$sale->customer_name] ?? null) : null;
                @endphp
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-4 mb-4">
                            <!-- Customer Image/Avatar -->
                            <div class="flex-shrink-0">
                                @if($customer && $customer->image)
                                    <img src="{{ asset('storage/'.$customer->image) }}" alt="{{ $customer->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-purple-200">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center border-2 border-purple-200">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Customer Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $sale->customer_name ?: __('messages.walk_in_customer') }}
                                </h3>
                                @if($customer && $customer->number)
                                    <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                        <i class="fas fa-phone text-xs"></i>
                                        {{ $customer->number }}
                                    </p>
                                @elseif($sale->customer_name)
                                    <p class="text-sm text-gray-400 italic mt-1">{{ __('messages.no_phone_number') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Top Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 flex items-center gap-2">
                            <a 
                                wire:navigate 
                                href="{{ localized_route('sales.details', $sale) }}" 
                                class="flex-1 text-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium transition-colors"
                                title="Details"
                            >
                                <i class="fas fa-eye mr-1"></i> {{ __('messages.details') }}
                            </a>
                            <a 
                                wire:navigate 
                                href="{{ localized_route('sales.edit', $sale) }}" 
                                class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"
                                title="{{ __('messages.edit') }}"
                            >
                                <i class="fas fa-edit mr-1"></i> {{ __('messages.edit') }}
                            </a>
                        </div>

                        <!-- Sale Details -->
                        <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.paid_amount') }}:</span>
                                <span class="text-lg font-bold text-green-600">Rs {{ number_format($sale->paid_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.total_items') }}:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $sale->saleItems->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.date') }}:</span>
                                <span class="text-sm text-gray-700">{{ $sale->date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.status') }}:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($sale->status === 'paid') bg-green-100 text-green-800
                                    @elseif($sale->status === 'unpaid') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                    {{ Str::ucfirst($sale->status ?: 'pending') }}
                                </span>
                            </div>
                        </div>

                        <!-- Bottom Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 flex items-center gap-2">
                            @php
                                $customer = $sale->customer_name ? ($customers[$sale->customer_name] ?? null) : null;
                            @endphp
                            @if($customer)
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('sales.add-sale', ['customer' => $customer->id]) }}" 
                                    class="flex-1 text-center px-3 py-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition-colors"
                                    title="Add Sale"
                                >
                                    <i class="fas fa-plus mr-1"></i> {{ __('messages.sale_entry') }}
                                </a>
                            @else
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('sales.add') }}" 
                                    class="flex-1 text-center px-3 py-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.add_sale') }}"
                                >
                                    <i class="fas fa-plus mr-1"></i> {{ __('messages.add_sale') }}
                                </a>
                            @endif
                            <button 
                                onclick="if(!confirm('{{ __('messages.are_you_sure_delete_sale') }}')) return false;" 
                                wire:click="deleteSale({{ $sale->id }})"
                                class="flex-1 text-center px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"
                                title="{{ __('messages.delete') }}"
                            >
                                <i class="fas fa-trash mr-1"></i> {{ __('messages.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white shadow-soft-xl rounded-2xl p-12 text-center">
                        <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">{{ __('messages.no_sales_found') }}</p>
                        <p class="text-gray-400 text-sm mt-2">{{ __('messages.start_by_creating_first_sale') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($sales->hasPages())
            <div class="flex justify-center py-6 mt-6 no-print">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
    <!-- Print Styles -->
<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 0;
    }
    
    body {
        margin: 0;
        padding: 0;
        background: white;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    body > * {
        visibility: hidden;
    }
    
    .max-w-7xl {
        visibility: visible;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 2rem;
        box-sizing: border-box;
    }
    
    .max-w-7xl * {
        visibility: visible;
    }
    
    .no-print, .no-print * {
        display: none !important;
        visibility: hidden !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .print-header {
        text-align: center;
        margin-bottom: 2rem;
        border-bottom: 2px solid #000;
        padding-bottom: 1rem;
    }
    
    .print-header h1 {
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0;
    }
    
    .print-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .print-table th, .print-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
        font-size: 10px;
        color: black;
    }
    
    .print-table th {
        background-color: #f3f4f6 !important;
        font-weight: bold;
    }
}
</style>

<!-- Print JavaScript -->
@script
<script>
    $wire.on('print-table', () => {
        window.print();
    });
</script>
@endscript

</div>

