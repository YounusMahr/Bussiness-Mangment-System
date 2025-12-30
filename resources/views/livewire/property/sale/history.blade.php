<div class="p-6">
    <div class="max-w-7xl mx-auto print-container">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Plot Sale History</h1>
                    <p class="text-slate-600 mt-1">Transaction history for {{ $sale->customer_name }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('property.sale.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        Back to Sales
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

        <!-- Sale Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 print-section">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2 no-print"></div>
            <div class="p-6 print-content">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Plot Sale Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 print-grid">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Customer Name</label>
                        <p class="text-base font-semibold text-slate-900">{{ $sale->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Customer Number</label>
                        <p class="text-base font-semibold text-slate-900">{{ $sale->customer_number }}</p>
                    </div>
                    @if($sale->plotPurchase)
                        <div>
                            <label class="text-sm font-medium text-slate-500">Plot Area</label>
                            <p class="text-base font-semibold text-slate-900">{{ $sale->plotPurchase->plot_area ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Location</label>
                            <p class="text-base font-semibold text-slate-900">{{ $sale->plotPurchase->location ?? 'N/A' }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-slate-500">Total Sale Price</label>
                        <p class="text-base font-semibold text-slate-900">Rs {{ number_format($sale->total_sale_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Paid</label>
                        <p class="text-base font-semibold text-green-600">Rs {{ number_format($sale->paid, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Remaining</label>
                        <p class="text-base font-semibold text-red-600">Rs {{ number_format($sale->remaining, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Sale Date</label>
                        <p class="text-base font-semibold text-slate-900">{{ $sale->date->format('Y-m-d') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Status</label>
                        <p class="text-base font-semibold {{ $sale->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($sale->status) }}</p>
                    </div>
                    @if($sale->installments)
                    <div class="md:col-span-2 lg:col-span-4">
                        <label class="text-sm font-medium text-slate-500">Installments</label>
                        <p class="text-base text-slate-900 whitespace-pre-wrap">{{ $sale->installments }}</p>
                    </div>
                    @endif
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions ?? [] as $index => $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->date->format('Y-m-d') ?? '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(isset($transaction->type) && $transaction->type === 'credit')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i>Credit
                                        </span>
                                    @elseif(isset($transaction->type) && $transaction->type === 'debit')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up mr-1"></i>Debit
                                        </span>
                                    @else
                                        <span class="text-gray-500">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if(isset($transaction->amount))
                                        <span class="font-medium {{ isset($transaction->type) && $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ isset($transaction->type) && $transaction->type === 'credit' ? '+' : '-' }}Rs {{ number_format($transaction->amount, 2) }}
                                        </span>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $transaction->description ?? '--' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $transaction->notes ?? '--' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                                        <p class="text-gray-500">No credit or debit transactions have been recorded yet for this plot sale.</p>
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
            
            .print-section:first-of-type .print-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1rem;
                margin: 0;
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
            
            #history-table th,
            #history-table td {
                border: 1px solid #000;
                padding: 6px 4px;
                text-align: left;
            }
            
            #history-table th {
                font-weight: bold;
                font-size: 8px;
                text-transform: uppercase;
                background-color: #2d2d2d !important;
                color: white !important;
            }
            
            #history-table tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }
        }
    </style>
</div>
