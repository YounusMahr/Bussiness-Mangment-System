<div class="p-6">
    <div class="max-w-7xl mx-auto print-container">
        <!-- Header -->
        <div class="mb-6 no-print">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('udhaar_report') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('detailed_udhaar_information') }}</p>
                </div>
                <div class="flex gap-2">
                    <a wire:navigate href="{{ localized_route('index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('back_to_dashboard') }}
                    </a>
                    <button wire:click="printReport" class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg">
                        <i class="fas fa-print"></i>
                        {{ __('print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6 no-print">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('filter_by') }}</label>
                        <select wire:model.live="filter" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="all">{{ __('all_records') }}</option>
                            <option value="daily">{{ __('daily') }}</option>
                            <option value="monthly">{{ __('monthly') }}</option>
                        </select>
                    </div>
                    @if($filter === 'daily')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('select_date') }}</label>
                            <input type="date" wire:model.live="selectedDate" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    @endif
                    @if($filter === 'monthly')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('select_month') }}</label>
                            <input type="month" wire:model.live="selectedMonth" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_udhaar') }}</p>
                    <p class="text-2xl font-bold text-purple-600">Rs {{ number_format($totalUdhaar, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_paid') }}</p>
                    <p class="text-2xl font-bold text-green-600">Rs {{ number_format($totalPaid, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_remaining') }}</p>
                    <p class="text-2xl font-bold text-red-600">Rs {{ number_format($totalRemaining, 2) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
                <div class="p-4">
                    <p class="text-sm font-medium text-slate-500 mb-1">{{ __('total_interest') }}</p>
                    <p class="text-2xl font-bold text-orange-600">Rs {{ number_format($totalInterest, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Udhaar Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden print-section">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200 no-print">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('udhaar_details') }}</h2>
            </div>
            <div class="print-table-header" style="display: none;">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('udhaar_details') }}</h2>
            </div>
            <div class="overflow-x-auto print-table-container">
                <table class="min-w-full divide-y divide-gray-200" id="udhaar-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('buy_date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('customer_name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('customer_number') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('total_amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('paid_amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('remaining_amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('interest_amount') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($udaars as $index => $udaar)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                    {{ $udaars->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $udaar->buy_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $udaar->customer_name ?? '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $udaar->customer_number ?? '--' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                    Rs {{ number_format($udaar->paid_amount + $udaar->remaining_amount - $udaar->interest_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    Rs {{ number_format($udaar->paid_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $udaar->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rs {{ number_format($udaar->remaining_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">
                                    Rs {{ number_format($udaar->interest_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $udaar->remaining_amount > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $udaar->remaining_amount > 0 ? __('remaining') : __('paid') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-file-invoice-dollar text-4xl text-gray-400 mb-4"></i>
                                        <p>{{ __('no_udhaar_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($udaars->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 no-print">
                    {{ $udaars->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('livewire.finance.print-styles', ['tableId' => 'udhaar-table'])
    
    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-report', () => {
            window.print();
        });
    });
    </script>
</div>
