<div class="p-6">
    <div class="max-w-7xl mx-auto print-container">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.cash_history') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.transaction_history_for') }} {{ $customer->name }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('grocery.cash.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('messages.back_to_cash_management') }}
                    </a>
                    <button 
                        wire:click="printHistory"
                        class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg"
                    >
                        <i class="fas fa-print"></i>
                        {{ __('messages.print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 print-section">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2 no-print"></div>
            <div class="p-6 print-content">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('messages.customer_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 print-grid">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.customer_name') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $customer->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.customer_number') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $customer->number ?: '--' }}</p>
                    </div>
                    @if($customer->email)
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.email') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $customer->email }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 no-print">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('messages.summary') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.total_credit') }}</label>
                        <p class="text-xl font-bold text-green-600">Rs {{ number_format($totalCredit, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.total_debit') }}</label>
                        <p class="text-xl font-bold text-red-600">Rs {{ number_format($totalDebit, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('messages.balance') }}</label>
                        <p class="text-xl font-bold {{ $finalBalance > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            Rs {{ number_format($finalBalance, 2) }}{{ $finalBalance > 0 ? ' dr' : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden print-section">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200 no-print">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.transaction_history') }}</h2>
            </div>
            <div class="print-table-header" style="display: none;">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.transaction_history') }}</h2>
            </div>
            <div class="overflow-x-auto print-table-container">
                <table class="min-w-full divide-y divide-gray-200" id="history-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.details') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.debit') }} (-)</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.credit') }} (+)</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $index => $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->date->format('d M y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <div>
                                        @if($transaction->notes)
                                            <div class="font-medium">{{ $transaction->notes }}</div>
                                        @else
                                            <div class="font-medium">{{ $transaction->type === 'cash-in' ? __('messages.credit') : __('messages.debit') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-right {{ $transaction->type === 'cash-out' ? 'bg-red-50 text-red-600' : 'text-gray-400' }}">
                                    @if($transaction->type === 'cash-out')
                                        Rs {{ number_format((float)($transaction->returned_amount ?? 0), 2) }} (-)
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-right {{ $transaction->type === 'cash-in' ? 'bg-green-50 text-green-600' : 'text-gray-400' }}">
                                    @if($transaction->type === 'cash-in')
                                        Rs {{ number_format((float)($transaction->return_amount ?? 0), 2) }} (+)
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-right {{ (float)($transaction->running_balance ?? 0) > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                    Rs {{ number_format((float)($transaction->running_balance ?? 0), 2) }}{{ (float)($transaction->running_balance ?? 0) > 0 ? ' dr' : '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                                        <p>{{ __('messages.no_transactions_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
            
            /* Customer Information Grid */
            .print-section:first-of-type .print-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 0;
                margin: 0;
            }
            
            .print-section:first-of-type .print-grid > div {
                padding: 1rem;
                border: 1px solid #000;
                margin: 0;
                margin-right: -1px;
                margin-bottom: -1px;
            }
            
            .print-section:first-of-type .print-grid > div:nth-child(3n) {
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
                font-size: 10px;
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
                font-size: 9px;
                text-transform: uppercase;
                background-color: #2d2d2d !important;
                color: white !important;
            }
            
            #history-table td {
                border: 1px solid #000;
                padding: 6px 4px;
                text-align: left;
                font-size: 10px;
                color: #000;
            }
            
            #history-table tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }
            
            #history-table tbody tr:hover {
                background-color: white !important;
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
                font-size: 9px !important;
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

    <!-- Print JavaScript -->
    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-history', () => {
            window.print();
        });
    });
    </script>
</div>
