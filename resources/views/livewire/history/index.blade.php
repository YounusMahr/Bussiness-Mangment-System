<div>
    <style>
        @media print {
            /* Hide elements with no-print class */
            .no-print, .no-print * {
                display: none !important;
            }
            /* Hide navbar and sidebar specifically */
            nav, aside {
                display: none !important;
            }
            /* Reset body background */
            body {
                background-color: white !important;
            }
            /* Remove shadows for cleaner print */
            .shadow-soft-xl {
                box-shadow: none !important;
                border: none !important;
            }
            /* Make main content span full width */
            main {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            /* Fix table overflow */
            .overflow-x-auto {
                overflow: visible !important;
            }
            table {
                width: 100% !important;
                table-layout: auto !important;
                font-size: 12px !important;
            }
            th, td {
                white-space: normal !important;
                word-wrap: break-word !important;
                padding: 8px 4px !important;
            }
            .max-w-xs {
                max-width: none !important;
            }
            /* Remove background from table header area if any */
            .bg-gray-50\/50 {
                background-color: white !important;
                border-bottom: none !important;
            }
            @page {
                size: landscape;
                margin: 10mm;
            }
        }
    </style>
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex-none w-full max-w-full">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="flex-none max-w-full">
                            <h6 class="font-bold text-xl text-slate-700">All Transaction History</h6>
                        </div>
                        <div class="no-print">
                            <button onclick="window.print()" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs bg-gradient-to-tl from-gray-900 to-slate-800">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-6 border-b border-gray-100 bg-gray-50/50 no-print">
                    <div class="md:col-span-4 lg:col-span-4">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Search</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" wire:model.live="search" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 pl-12 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" placeholder="Search entity, module, notes..." />
                        </div>
                    </div>

                    <div class="md:col-span-3 lg:col-span-2">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Entry Type</label>
                        <select wire:model.live="moduleFilter" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                            <option value="all">All Modules</option>
                            <option value="Khata">Khata (Udhaar)</option>
                            <option value="Credit">Credit (Grocery Cash)</option>
                            <option value="Car Installment">Car Installment</option>
                            <option value="Plot Sale">Plot Sale</option>
                            <option value="Plot Purchase">Plot Purchase</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2 lg:col-span-2">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Quick Filter</label>
                        <select wire:model.live="dateFilter" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow">
                            <option value="all">All Time</option>
                            <option value="daily">Today</option>
                            <option value="monthly">This Month</option>
                            <option value="yearly">This Year</option>
                        </select>
                    </div>

                    <div class="md:col-span-3 lg:col-span-4">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Specific Date</label>
                        <input type="date" wire:model.live="selectedDate" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none focus:transition-shadow" />
                    </div>
                </div>

                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Date</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Module</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Type</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Entity/Customer</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Amount</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-4 py-1">
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal">{{ \Carbon\Carbon::parse($transaction->date)->format('d M, Y') }}</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <p class="mb-0 text-sm font-semibold leading-tight px-4 text-slate-700">{{ $transaction->module }}</p>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white mx-4">
                                            {{ str_replace(['-', '_'], ' ', $transaction->type) }}
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <p class="mb-0 text-sm font-semibold leading-tight px-4 text-slate-700">{{ $transaction->entity ?? 'N/A' }}</p>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <p class="mb-0 text-sm font-bold leading-tight px-4 text-green-600">Rs. {{ number_format($transaction->amount, 2) }}</p>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b shadow-transparent">
                                        <p class="mb-0 text-sm leading-tight px-4 text-slate-500 max-w-xs truncate" title="{{ $transaction->notes }}">{{ $transaction->notes ?? '-' }}</p>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-sm font-medium text-slate-500 border-b">
                                        No transaction history found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t no-print">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
