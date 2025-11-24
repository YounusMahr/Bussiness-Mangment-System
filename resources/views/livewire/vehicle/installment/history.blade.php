<div class="p-6">
    <div class="max-w-7xl mx-auto print-container">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('installment_history') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('transaction_history_for') }} {{ $installment->customer->name ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('vehicle.installment.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('back_to_installments') }}
                    </a>
                    <button 
                        wire:click="printHistory"
                        class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg"
                    >
                        <i class="fas fa-print"></i>
                        {{ __('print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Installment Information Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 print-section">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2 no-print"></div>
            <div class="p-6 print-content">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('installment_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 print-grid">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('customer_name') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->customer->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('phone_number') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->number ?: '--' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('vehicle') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->vehicle ?? '--' }} {{ $installment->model ? '(' . $installment->model . ')' : '' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('date') }}</label>
                        <p class="text-base font-semibold text-slate-900">{{ $installment->date->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Status Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 no-print">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ __('current_status') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('car_price') }}</label>
                        <p class="text-xl font-bold text-blue-600">Rs {{ number_format($installment->car_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('interest') }}</label>
                        <p class="text-xl font-bold text-purple-600">Rs {{ number_format($installment->interest, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('total_price') }}</label>
                        <p class="text-xl font-bold text-indigo-600">Rs {{ number_format($installment->total_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('paid') }}</label>
                        <p class="text-xl font-bold text-green-600">Rs {{ number_format($installment->paid, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">{{ __('remaining') }}</label>
                        <p class="text-xl font-bold {{ $installment->remaining > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rs {{ number_format($installment->remaining, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden print-section">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200 no-print">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('transaction_history') }}</h2>
            </div>
            <div class="print-table-header" style="display: none;">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('transaction_history') }}</h2>
            </div>
            <div class="overflow-x-auto print-table-container">
                <table class="min-w-full divide-y divide-gray-200" id="history-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('car_price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('interest') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('paid') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('return_payment') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('total_price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('remaining') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('notes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $index => $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->type === 'add')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-plus mr-1"></i>{{ __('add') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i>{{ __('return') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($transaction->type === 'add' && $transaction->new_car_price > 0)
                                        Rs {{ number_format($transaction->new_car_price, 2) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($transaction->type === 'add' && $transaction->new_interest > 0)
                                        Rs {{ number_format($transaction->new_interest, 2) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'add' ? 'text-green-600' : 'text-gray-400' }}">
                                    @if($transaction->type === 'add' && $transaction->new_paid > 0)
                                        Rs {{ number_format($transaction->new_paid, 2) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'return' ? 'text-green-600' : 'text-gray-400' }}">
                                    @if($transaction->type === 'return' && $transaction->return_payment > 0)
                                        Rs {{ number_format($transaction->return_payment, 2) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                    @if($transaction->type === 'add')
                                        Rs {{ number_format($transaction->new_total_price, 2) }}
                                    @else
                                        Rs {{ number_format($transaction->total_price_after, 2) }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->remaining_after > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rs {{ number_format($transaction->remaining_after, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $transaction->notes ?: '--' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-history text-4xl text-gray-400 mb-4"></i>
                                        <p>{{ __('no_transactions_found') }}</p>
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
            
            /* Installment Information Grid */
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
