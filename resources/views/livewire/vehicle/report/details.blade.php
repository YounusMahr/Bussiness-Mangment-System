<div class="p-6">
    <div class="max-w-7xl mx-auto print-container">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('vehicle_installment_report') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('detailed_vehicle_installment_information') }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('vehicle.report.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('back_to_dashboard') }}
                    </a>
                    <button 
                        wire:click="printReport"
                        class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg"
                    >
                        <i class="fas fa-print"></i>
                        {{ __('print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 no-print">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('filter_by') }}</label>
                        <select wire:model.live="filter" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">{{ __('all_records') }}</option>
                            <option value="daily">{{ __('daily') }}</option>
                            <option value="monthly">{{ __('monthly') }}</option>
                        </select>
                    </div>
                    @if($filter === 'daily')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('select_date') }}</label>
                            <input type="date" wire:model.live="selectedDate" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    @endif
                    @if($filter === 'monthly')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('select_month') }}</label>
                            <input type="month" wire:model.live="selectedMonth" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 no-print">
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_car_price') }}</p>
                    <p class="text-2xl font-bold text-blue-600">Rs {{ number_format($totalCarPrice, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_sales') }}</p>
                    <p class="text-2xl font-bold text-indigo-600">Rs {{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_paid') }}</p>
                    <p class="text-2xl font-bold text-green-600">Rs {{ number_format($totalPaid, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_remaining') }}</p>
                    <p class="text-2xl font-bold text-red-600">Rs {{ number_format($totalRemaining, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_interest') }}</p>
                    <p class="text-2xl font-bold text-orange-600">Rs {{ number_format($totalInterest, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Installments Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden print-section">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200 no-print">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('installment_details') }}</h2>
            </div>
            <div class="print-table-header" style="display: none;">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('installment_details') }}</h2>
            </div>
            <div class="overflow-x-auto print-table-container">
                <table class="min-w-full divide-y divide-gray-200" id="installments-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('customer_name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('phone_number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('vehicle') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('model') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('car_price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('interest') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('total_price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('paid') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('remaining') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($installments as $index => $installment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                    {{ $installments->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $installment->date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $installment->customer->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $installment->number ?? $installment->customer->number ?? '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $installment->vehicle ?? '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $installment->model ?? '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                    Rs {{ number_format($installment->car_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">
                                    Rs {{ number_format($installment->interest ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                    Rs {{ number_format($installment->total_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    Rs {{ number_format($installment->paid, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $installment->remaining > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rs {{ number_format($installment->remaining, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-car text-4xl text-gray-400 mb-4"></i>
                                        <p>{{ __('no_installments_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($installments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 no-print">
                    {{ $installments->links() }}
                </div>
            @endif
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
            
            /* Transaction History Table */
            .print-section .print-table-header {
                display: block !important;
                padding: 2rem 2rem 1rem 2rem;
                border-bottom: 2px solid #000;
            }
            
            .print-section .print-table-header h2 {
                font-size: 18px;
                font-weight: 700;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: #000;
            }
            
            .print-section .print-table-container {
                padding: 0 2rem 2rem 2rem;
            }
            
            #installments-table {
                border-collapse: collapse;
                width: 100%;
                font-size: 9px;
                margin-top: 1rem;
                color: #000;
            }
            
            #installments-table thead {
                background-color: #2d2d2d !important;
                color: white !important;
            }
            
            #installments-table th {
                border: 1.5px solid #000;
                padding: 6px 4px;
                text-align: left;
                font-weight: bold;
                font-size: 8px;
                text-transform: uppercase;
                background-color: #2d2d2d !important;
                color: white !important;
            }
            
            #installments-table td {
                border: 1px solid #000;
                padding: 6px 4px;
                text-align: left;
                font-size: 9px;
                color: #000;
            }
            
            #installments-table tbody tr:nth-child(even) {
                background-color: #f5f5f5;
            }
            
            #installments-table tbody tr:hover {
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
            
            /* Remove colors from text */
            * {
                color: #000 !important;
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
        Livewire.on('print-report', () => {
            window.print();
        });
    });
    </script>
</div>
