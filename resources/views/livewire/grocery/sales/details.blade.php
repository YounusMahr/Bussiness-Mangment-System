<div class="p-6 print-container">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('sale_details') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('sale_information_for') }} {{ $sale->customer_name ?: __('walk_in_customer') }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('sales') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('back_to_sales') }}
                    </a>
                    <button 
                        wire:click="printDetails"
                        class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg transition-colors"
                    >
                        <i class="fas fa-print"></i>
                        {{ __('print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 print-section">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('customer_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('customer_name') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $sale->customer_name ?: __('walk_in_customer') }}</p>
                    </div>
                    @if($sale->customer_name)
                        @php
                            $customer = \App\Models\Customer::where('name', $sale->customer_name)->where('type', 'Grocery')->first();
                        @endphp
                        @if($customer)
                            <div>
                                <label class="text-sm font-medium text-slate-500">{{ __('phone_number') }}</label>
                                <p class="text-base font-semibold text-slate-900">{{ $customer->number ?: '--' }}</p>
                            </div>
                            @if($customer->email)
                                <div>
                                    <label class="text-sm font-medium text-slate-500">{{ __('email') }}</label>
                                    <p class="text-base font-semibold text-slate-900">{{ $customer->email }}</p>
                                </div>
                            @endif
                        @endif
                    @endif
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('sale_date') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $sale->date->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('payment_method') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $sale->payment_method ?: '--' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('status') }}</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sale->status === 'paid' ? 'bg-green-100 text-green-800' : ($sale->status === 'unpaid' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($sale->status ?: 'pending') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sale Summary Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 no-print">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('sale_summary') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('total_items') }}</label>
                        <p class="text-xl font-bold text-slate-900">{{ $sale->saleItems->sum('quantity') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('subtotal') }}</label>
                        <p class="text-xl font-bold text-slate-900">Rs {{ number_format($sale->saleItems->sum('total'), 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('discount') }}</label>
                        <p class="text-xl font-bold text-red-600">Rs {{ number_format($sale->discount ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('total_price') }}</label>
                        <p class="text-xl font-bold text-green-600">Rs {{ number_format($sale->total_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('paid_amount') }}</label>
                        <p class="text-xl font-bold text-blue-600">Rs {{ number_format($sale->paid_amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('remaining_amount') }}</label>
                        <p class="text-xl font-bold text-orange-600">Rs {{ number_format($sale->total_price - $sale->paid_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sale Items Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden print-section">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('sale_items') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="details-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('product_name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('category') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('quantity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('unit_price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('discount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('total') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('notes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sale->saleItems as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-white text-sm"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product ? $item->product->name : 'N/A' }}</div>
                                            @if($item->product)
                                                <div class="text-xs text-gray-500">Stock: {{ $item->product->quantity }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $item->category ? $item->category->name : '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="font-medium">{{ number_format($item->quantity, 0) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    Rs {{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($item->discount > 0)
                                        <span class="text-red-600 font-medium">Rs {{ number_format($item->discount, 2) }}</span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    Rs {{ number_format($item->total, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $item->notes ?: '--' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('no_items_found') }}</h3>
                                        <p class="text-gray-500">{{ __('no_items_in_this_sale') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                {{ __('subtotal') }}:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                Rs {{ number_format($sale->saleItems->sum('total'), 2) }}
                            </td>
                            <td></td>
                        </tr>
                        @if($sale->discount > 0)
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ __('discount') }}:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">
                                    - Rs {{ number_format($sale->discount, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                {{ __('total') }}:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 text-lg">
                                Rs {{ number_format($sale->total_price, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($sale->notes)
            <!-- Notes Card -->
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mt-6 print-section">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('notes') }}</h2>
                </div>
                <div class="p-6">
                    <p class="text-slate-700 whitespace-pre-wrap">{{ $sale->notes }}</p>
                </div>
            </div>
        @endif
    </div>

    @script
    <script>
        Livewire.on('print-details', () => {
            window.print();
        });
    </script>
    @endscript

    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }
            
            /* Reset body for print */
            body {
                margin: 0;
                padding: 0;
                background: white;
                font-family: 'Arial', 'Helvetica', sans-serif;
            }
            
            /* Hide everything by default */
            body > * {
                visibility: hidden;
            }
            
            /* Show only print sections in proper flow */
            .p-6 {
                visibility: visible;
                position: relative;
                width: 100%;
                padding: 3rem 2rem;
                margin: 0;
                box-sizing: border-box;
            }
            
            .p-6 > div {
                max-width: 100% !important;
                margin: 0 !important;
            }
            
            .print-section,
            .print-section * {
                visibility: visible;
            }
            
            /* Hide header, buttons, and navigation */
            .mb-6:first-child,
            button,
            a,
            .no-print,
            .no-print * {
                display: none !important;
                visibility: hidden !important;
            }
            
            /* Report Header - Customer Information Section */
            .print-section:first-of-type {
                position: relative;
                width: 100%;
                margin: 0 0 0 0;
                padding: 0;
                border: 2px solid #000;
                background: white;
                page-break-after: avoid;
                box-sizing: border-box;
            }
            
            .print-section:first-of-type > div {
                padding: 2rem;
            }
            
            .print-section:first-of-type h2 {
                font-size: 18px;
                font-weight: 700;
                margin: 0 0 1.5rem 0;
                padding-bottom: 0.75rem;
                border-bottom: 2px solid #000;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
            }
            
            .print-section:first-of-type .grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0;
                margin: 0;
            }
            
            .print-section:first-of-type .grid > div {
                padding: 1rem;
                border: 1px solid #000;
                margin: 0;
                margin-right: -1px;
                margin-bottom: -1px;
            }
            
            .print-section:first-of-type .grid > div:nth-child(3n) {
                margin-right: 0;
            }
            
            .print-section:first-of-type label {
                font-size: 10px;
                font-weight: 700;
                color: #000;
                text-transform: uppercase;
                display: block;
                margin-bottom: 0.5rem;
                letter-spacing: 0.5px;
            }
            
            .print-section:first-of-type p {
                font-size: 14px;
                margin: 0;
                font-weight: 600;
                color: #000;
                line-height: 1.5;
            }
            
            .print-section:first-of-type span {
                font-size: 12px;
                padding: 0.375rem 0.75rem;
                border: 1.5px solid #000;
                display: inline-block;
                font-weight: 600;
                background: white;
                color: #000;
            }
            
            /* Sale Items Table - Details Section */
            .print-section:nth-of-type(2) {
                position: relative;
                width: 100%;
                margin: 0 0 0 0;
                padding: 0;
                border: 2px solid #000;
                background: white;
                page-break-inside: avoid;
                box-sizing: border-box;
            }
            
            .print-section:nth-of-type(2) > div:first-child {
                margin: 0;
                padding: 2rem 2rem 1rem 2rem;
                border-bottom: 2px solid #000;
            }
            
            .print-section:nth-of-type(2) > div:last-child {
                padding: 0 2rem 2rem 2rem;
            }
            
            .print-section:nth-of-type(2) h2 {
                font-size: 18px;
                font-weight: 700;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
            }
            
            /* Style table for print */
            #details-table {
                border-collapse: collapse;
                width: 100%;
                font-size: 11px;
                margin-top: 1rem;
            }
            
            #details-table thead {
                background-color: white !important;
            }
            
            #details-table th {
                border: 1.5px solid #000;
                padding: 0.75rem 0.5rem;
                text-align: left;
                font-weight: 700;
                font-size: 10px;
                text-transform: uppercase;
                background-color: white !important;
                color: #000;
                letter-spacing: 0.5px;
            }
            
            #details-table td {
                border: 1px solid #000;
                padding: 0.75rem 0.5rem;
                text-align: left;
                font-size: 11px;
                color: #000;
                vertical-align: top;
                background-color: white !important;
            }
            
            #details-table tbody tr:nth-child(even) {
                background-color: white !important;
            }
            
            #details-table tbody tr:hover {
                background-color: white !important;
            }
            
            #details-table tfoot {
                background-color: white !important;
                font-weight: 700;
            }
            
            #details-table tfoot td {
                border-top: 2px solid #000;
                border-bottom: 1px solid #000;
                padding: 1rem 0.5rem;
                font-size: 12px;
                color: #000;
                background-color: white !important;
            }
            
            #details-table tfoot tr:last-child td {
                border-bottom: 2px solid #000;
                font-size: 13px;
                background-color: white !important;
                font-weight: 800;
            }
            
            /* Notes Section */
            .print-section:nth-of-type(3) {
                position: relative;
                width: 100%;
                margin: 0;
                padding: 0;
                border: 2px solid #000;
                background: white;
                page-break-inside: avoid;
                box-sizing: border-box;
            }
            
            .print-section:nth-of-type(3) > div:first-child {
                margin: 0;
                padding: 2rem 2rem 1rem 2rem;
                border-bottom: 2px solid #000;
            }
            
            .print-section:nth-of-type(3) > div:last-child {
                padding: 2rem;
            }
            
            .print-section:nth-of-type(3) h2 {
                font-size: 18px;
                font-weight: 700;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
            }
            
            .print-section:nth-of-type(3) p {
                font-size: 12px;
                line-height: 1.8;
                color: #000;
                margin: 0;
            }
            
            /* Remove gradients and shadows for print */
            .bg-gradient-to-r {
                display: none !important;
            }
            
            .rounded-2xl {
                border-radius: 0 !important;
            }
            
            .shadow-soft-xl {
                box-shadow: none !important;
            }
            
            /* Hide images and icons in print */
            img {
                display: none;
            }
            
            .fas, .fa {
                display: none;
            }
            
            /* Ensure proper spacing and flow */
            .print-section {
                page-break-inside: avoid;
            }
            
            /* Remove any overflow issues */
            .overflow-x-auto {
                overflow: visible !important;
            }
            
            /* Professional typography */
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</div>
