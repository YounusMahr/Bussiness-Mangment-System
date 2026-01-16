<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.cash_history') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.all_transactions') }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800 px-4 py-2 bg-slate-100 rounded-lg">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 h-2"></div>
            <div class="p-6">
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
                        <p class="text-xl font-bold {{ $finalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rs {{ number_format($finalBalance, 2) }}
                            @if($finalBalance < 0)
                                <span class="text-sm">dr</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-2xl shadow-soft-xl p-4 mb-6">
            <div class="relative">
                <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500">
                    <i class="fas fa-search"></i>
                </span>
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="{{ __('messages.search_by_customer_name_number_or_notes') }}"
                    class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                >
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.transaction_history') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
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
                                    {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->date->format('d M y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    <div>
                                        <div class="font-medium">{{ $transaction->customer->name ?? 'N/A' }}</div>
                                        @if($transaction->notes)
                                            <div class="text-xs text-gray-500 mt-1">{{ $transaction->notes }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-red-600 text-right">
                                    @if($transaction->type === 'cash-out')
                                        Rs {{ number_format((float)($transaction->returned_amount ?? 0), 2) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-green-600 text-right">
                                    @if($transaction->type === 'cash-in')
                                        Rs {{ number_format((float)($transaction->return_amount ?? 0), 2) }}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-right {{ ($transaction->running_balance ?? 0) >= 0 ? 'text-gray-700' : 'text-red-600' }}">
                                    Rs {{ number_format((float)($transaction->running_balance ?? 0), 2) }}
                                    @if(($transaction->running_balance ?? 0) < 0)
                                        <span class="text-xs">dr</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                    <p>{{ __('messages.no_transactions_found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
