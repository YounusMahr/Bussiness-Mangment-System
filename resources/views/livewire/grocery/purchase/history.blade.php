<div class="p-6">
    <div class="max-w-7xl mx-auto print-container">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Stock Purchase History</h1>
                    <p class="text-slate-600 mt-1">Transaction history for {{ $purchase->seller_name }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('purchases.bulk') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        Back to Purchases
                    </a>
                    <button 
                        wire:click="printHistory"
                        class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg"
                    >
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Purchase Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 print-section">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2 no-print"></div>
            <div class="p-6 print-content">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Purchase Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 print-grid">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Seller Name</label>
                        <p class="text-base font-semibold text-slate-900">{{ $purchase->seller_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Phone Number</label>
                        <p class="text-base font-semibold text-slate-900">{{ $purchase->contact ?: '--' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Goods Name</label>
                        <p class="text-base font-semibold text-slate-900">{{ $purchase->goods_name ?: '--' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Initial Date</label>
                        <p class="text-base font-semibold text-slate-900">{{ $purchase->date->format('Y-m-d') }}</p>
                    </div>
                    @if($purchase->time_period)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Time Period</label>
                        <p class="text-base font-semibold text-slate-900">{{ $purchase->time_period }}</p>
                    </div>
                    @endif
                    @if($purchase->due_date)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Due Date</label>
                        <p class="text-base font-semibold text-slate-900 {{ $purchase->due_date < now() ? 'text-red-600' : ($purchase->due_date->diffInDays(now()) <= 7 ? 'text-yellow-600' : '') }}">
                            {{ $purchase->due_date->format('Y-m-d') }}
                        </p>
                    </div>
                    @endif
                    @if($purchase->notes)
                    <div class="md:col-span-2 lg:col-span-4">
                        <label class="text-sm font-medium text-slate-500">Notes</label>
                        <p class="text-base text-slate-900 whitespace-pre-wrap">{{ $purchase->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Current Status Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 no-print">
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Current Status</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Stock Information Column -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-3">Stock Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-slate-500">Total Stock</label>
                                <p class="text-xl font-bold text-slate-900">{{ number_format($purchase->total_stock, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Given Stock</label>
                                <p class="text-xl font-bold text-green-600">{{ number_format($purchase->given_stock, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Remaining Stock</label>
                                <p class="text-xl font-bold text-red-600">{{ number_format($purchase->remaining_stock, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Status</label>
                                <p class="text-xl font-bold {{ $purchase->status === 'complete' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($purchase->status) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Information Column -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-3">Financial Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-slate-500">Goods Total Price</label>
                                <p class="text-xl font-bold text-slate-900">Rs {{ number_format($purchase->goods_total_price, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Paid</label>
                                <p class="text-xl font-bold text-blue-600">Rs {{ number_format($purchase->paid, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Remaining</label>
                                <p class="text-xl font-bold text-red-600">Rs {{ number_format($purchase->remaining, 2) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-500">Total Remaining</label>
                                <p class="text-xl font-bold text-orange-600">Rs {{ number_format($purchase->total_remaining, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden print-section">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200 no-print">
                <h2 class="text-lg font-semibold text-slate-900">Transaction History</h2>
            </div>
            <div class="print-table-header" style="display: none;">
                <h2 class="text-lg font-semibold text-slate-900">Transaction History</h2>
            </div>
            <div class="overflow-x-auto print-table-container">
                <table class="min-w-full divide-y divide-gray-200" id="history-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Goods</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Financial Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Before</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock After</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Before</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment After</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $index => $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->type === 'stock-in')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i>Credit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up mr-1"></i>Debit
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->new_goods_name ?: '--' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($transaction->type === 'stock-in')
                                        <div class="space-y-1">
                                            <div>New Stock: <span class="font-medium">{{ number_format($transaction->new_total_stock, 2) }}</span></div>
                                            <div>New Given: <span class="font-medium text-green-600">{{ number_format($transaction->new_given_stock, 2) }}</span></div>
                                        </div>
                                    @else
                                        <div class="space-y-1">
                                            <div>Return Stock: <span class="font-medium text-red-600">{{ number_format($transaction->return_stock, 2) }}</span></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($transaction->type === 'stock-in')
                                        <div class="space-y-1">
                                            <div>Price: <span class="font-medium">Rs {{ number_format($transaction->new_goods_total_price, 2) }}</span></div>
                                            <div>Paid: <span class="font-medium text-blue-600">Rs {{ number_format($transaction->new_paid, 2) }}</span></div>
                                            @if($transaction->new_interest > 0)
                                                <div>Interest: <span class="font-medium text-orange-600">Rs {{ number_format($transaction->new_interest, 2) }}</span></div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="space-y-1">
                                            <div>Return Payment: <span class="font-medium text-red-600">Rs {{ number_format($transaction->return_payment, 2) }}</span></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="space-y-1">
                                        <div>Total: <span class="font-medium">{{ number_format($transaction->total_stock_before, 2) }}</span></div>
                                        <div>Remaining: <span class="font-medium text-red-600">{{ number_format($transaction->remaining_stock_before, 2) }}</span></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="space-y-1">
                                        <div>Total: <span class="font-medium">{{ number_format($transaction->total_stock_after, 2) }}</span></div>
                                        <div>Remaining: <span class="font-medium text-red-600">{{ number_format($transaction->remaining_stock_after, 2) }}</span></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="space-y-1">
                                        <div>Paid: <span class="font-medium text-blue-600">Rs {{ number_format($transaction->paid_before, 2) }}</span></div>
                                        <div>Remaining: <span class="font-medium text-red-600">Rs {{ number_format($transaction->remaining_before, 2) }}</span></div>
                                        <div>Total Remaining: <span class="font-medium text-orange-600">Rs {{ number_format($transaction->total_remaining_before, 2) }}</span></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="space-y-1">
                                        <div>Paid: <span class="font-medium text-blue-600">Rs {{ number_format($transaction->paid_after, 2) }}</span></div>
                                        <div>Remaining: <span class="font-medium text-red-600">Rs {{ number_format($transaction->remaining_after, 2) }}</span></div>
                                        <div>Total Remaining: <span class="font-medium text-orange-600">Rs {{ number_format($transaction->total_remaining_after, 2) }}</span></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $transaction->notes ?: '--' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                                        <p class="text-gray-500">No stock in or stock out transactions have been recorded yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @script
    <script>
        Livewire.on('print-history', () => {
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
            
            .print-container {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 3rem 2rem;
                box-sizing: border-box;
            }
            
            .print-container * {
                visibility: visible;
            }
            
            .no-print, .no-print * {
                display: none !important;
                visibility: hidden !important;
            }
            
            .print-section {
                position: relative;
                width: 100%;
                margin-bottom: 0;
                border: 2px solid #000;
                background: white;
                page-break-inside: avoid;
                box-sizing: border-box;
            }
            
            .print-section:not(:last-of-type) {
                margin-bottom: 0;
            }
            
            .print-section:last-of-type {
                margin-bottom: 0;
            }
            
            .print-section .print-content {
                padding: 2rem;
            }
            
            .print-section h2 {
                font-size: 18px;
                font-weight: 700;
                margin: 0 0 1.5rem 0;
                padding-bottom: 0.75rem;
                border-bottom: 3px solid #000;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
            }
            
            /* Purchase Information Grid */
            .print-section:first-of-type .print-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1rem;
                margin: 0;
            }
            
            .print-section:first-of-type .print-grid > div {
                padding: 0;
                border: none;
                margin: 0;
            }
            
            .print-section:first-of-type .print-grid > div.md\:col-span-2,
            .print-section:first-of-type .print-grid > div.lg\:col-span-4 {
                grid-column: 1 / -1;
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
            
            /* Transaction History Table */
            .print-section:nth-of-type(2) {
                margin-top: 0;
            }
            
            .print-section:nth-of-type(2) .print-table-header {
                display: block !important;
                padding: 2rem 2rem 1rem 2rem;
                border-bottom: 2px solid #000;
            }
            
            .print-section:nth-of-type(2) .print-table-header h2 {
                font-size: 18px;
                font-weight: 700;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
            }
            
            .print-section:nth-of-type(2) .print-table-container {
                padding: 0 2rem 2rem 2rem;
            }
            
            #history-table {
                border-collapse: collapse;
                width: 100%;
                font-size: 9px;
                margin-top: 1rem;
                color: #000;
            }
            
            #history-table thead {
                background-color: #2d2d2d !important;
                color: white !important;
            }
            
            #history-table th {
                border: 1.5px solid #000;
                padding: 6px 4px;
                text-align: left;
                font-weight: bold;
                font-size: 8px;
                text-transform: uppercase;
                background-color: #2d2d2d !important;
                color: white !important;
            }
            
            #history-table td {
                border: 1px solid #000;
                padding: 6px 4px;
                text-align: left;
                font-size: 9px;
                color: #000;
            }
            
            #history-table tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }
            
            #history-table tbody tr:hover {
                background-color: white !important;
            }
            
            #history-table .space-y-1 {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }
            
            #history-table .space-y-1 > div {
                font-size: 8px;
                line-height: 1.3;
            }
            
            /* Remove gradients and shadows for print */
            .bg-gradient-to-r, .bg-gradient-to-br {
                background: none !important;
                height: auto !important;
            }
            
            .rounded-2xl {
                border-radius: 0 !important;
            }
            
            .shadow-soft-xl {
                box-shadow: none !important;
            }
            
            /* Hide images and icons in print */
            img, .fas, .fa {
                display: none;
            }
            
            /* Remove colors from spans and badges */
            span {
                color: #000 !important;
                background: white !important;
                border: 1px solid #000 !important;
                padding: 0.25rem 0.5rem !important;
                font-size: 8px !important;
            }
            
            /* Ensure proper spacing and flow */
            .print-section {
                page-break-inside: avoid;
            }
            
            /* Remove any overflow issues */
            .overflow-x-auto {
                overflow: visible !important;
            }
        }
    </style>
</div>

