<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.stock_purchases_management') }}</h1>
                </div>
                <div class="flex justify-between w-full">
                    <p class="text-gray-600 mt-1">{{ __('messages.manage_your_stock_purchases') }}</p>
                    <div class="flex gap-2">
                        <a 
                            wire:navigate
                            href="{{ localized_route('purchases.add') }}"
                            class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white md:font-bold font-normal py-1 px-2 md:py-2 md:px-2 rounded-lg flex items-center gap-2"
                        >
                            <i class="fas fa-plus"></i>
                            {{ __('messages.add_purchase') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                            <i class="fas fa-search"></i>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="{{ __('messages.search_by_goods_name_seller_contact') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-full leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
                <div>
                    <button 
                        wire:click="printTable" 
                        class="bg-green-200 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-print"></i>
                        {{ __('messages.print') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Stock Purchases Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($purchases as $purchase)
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Seller Info -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                                    <i class="fas fa-store text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $purchase->seller_name }}</h3>
                                    @if($purchase->contact)
                                        <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                            <i class="fas fa-phone text-xs"></i>
                                            {{ $purchase->contact }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $purchase->status === 'complete' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                {{ $purchase->status === 'complete' ? __('messages.complete') : __('messages.incomplete') }}
                            </span>
                        </div>

                        <!-- Stock Details -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.date') }}:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $purchase->date->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.total_stock') }}:</span>
                                <span class="text-sm font-bold text-blue-600">{{ number_format($purchase->total_stock, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.given_stock') }}:</span>
                                <span class="text-sm font-bold text-green-600">{{ number_format($purchase->given_stock, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('messages.remaining_stock') }}:</span>
                                <span class="text-sm font-bold text-red-600">{{ number_format($purchase->remaining_stock, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                            <!-- Stock In/Out Buttons -->
                            <div class="flex gap-2">
                                <a 
                                    wire:navigate
                                    href="{{ localized_route('purchases.stock-out', $purchase) }}"
                                    class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    title="Add Stock"
                                >
                                    <i class="fas fa-arrow-down mr-1"></i> {{ __('messages.stock_in') }}
                                </a>
                                <a 
                                    wire:navigate
                                     href="{{ localized_route('purchases.stock-in', $purchase) }}"
                                    class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    title="{{ __('messages.return_stock') }}"
                                >
                                    <i class="fas fa-arrow-up mr-1"></i> {{ __('messages.stock_out') }}
                                </a>
                            </div>
                            
                            <!-- Other Actions -->
                            <div class="flex items-center justify-between gap-2">
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('purchases.history', $purchase) }}" 
                                    class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.view_history') }}"
                                >
                                    <i class="fas fa-history mr-1"></i> {{ __('messages.history') }}
                                </a>
                                <a 
                                    wire:navigate 
                                    href="{{ localized_route('purchases.edit', $purchase) }}" 
                                    class="flex-1 text-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.edit_purchase') }}"
                                >
                                    <i class="fas fa-edit mr-1"></i> {{ __('messages.edit') }}
                                </a>
                                <button 
                                    wire:click="confirmDelete({{ $purchase->id }})"
                                    class="px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"
                                    title="{{ __('messages.delete_purchase') }}"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white shadow-soft-xl rounded-2xl p-12 text-center">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.no_stock_purchases_found') }}</h3>
                        <p class="text-gray-500 mb-4">{{ __('messages.get_started_by_creating_first_stock_purchase') }}</p>
                        <a wire:navigate href="{{ localized_route('purchases.add') }}" class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg">
                            {{ __('messages.add_purchase') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($purchases->hasPages())
            <div class="flex justify-center py-6 mt-6">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-4 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-2xl bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $editingPurchase ? 'Edit Stock Purchase' : 'Create New Stock Purchase' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                                <input 
                                    type="date" 
                                    wire:model="date"
                                    id="date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                @error('date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="seller_name" class="block text-sm font-medium text-gray-700 mb-2">Seller Name *</label>
                                <input 
                                    type="text" 
                                    wire:model="seller_name"
                                    id="seller_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Enter seller name"
                                >
                                @error('seller_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
                                <input 
                                    type="text" 
                                    wire:model="contact"
                                    id="contact"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Enter contact number"
                                >
                                @error('contact') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="total_stock" class="block text-sm font-medium text-gray-700 mb-2">Total Stock *</label>
                                <input 
                                    type="number" 
                                    wire:model.live="total_stock"
                                    id="total_stock"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                @error('total_stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="given_stock" class="block text-sm font-medium text-gray-700 mb-2">Given Stock *</label>
                                <input 
                                    type="number" 
                                    wire:model.live="given_stock"
                                    id="given_stock"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                @error('given_stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="remaining_stock" class="block text-sm font-medium text-gray-700 mb-2">Remaining Stock</label>
                                <input 
                                    type="number" 
                                    wire:model="remaining_stock"
                                    id="remaining_stock"
                                    step="0.01"
                                    min="0"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                                >
                                <p class="text-xs text-gray-500 mt-1">Auto-calculated</p>
                                @error('remaining_stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select 
                                    wire:model="status"
                                    id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                    <option value="remaining">Remaining</option>
                                    <option value="complete">Complete</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea 
                                    wire:model="notes"
                                    id="notes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Enter any additional notes"
                                ></textarea>
                                @error('notes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button 
                                type="button" 
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg transition-colors"
                            >
                                {{ $editingPurchase ? 'Update Purchase' : 'Create Purchase' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Confirmation Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
                <div class="mb-5">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Delete Stock Purchase?</h3>
                    <p class="text-slate-600 text-sm">Are you sure you want to delete this stock purchase? This action cannot be undone.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">Yes, Delete</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">Cancel</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Print Styles -->
    <style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            font-size: 12px;
        }
        
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        th, td {
            border: 1px solid #000 !important;
            padding: 8px !important;
        }
        
        .bg-gradient-to-r {
            background:rgb(5, 241, 80) !important;
        }
    }
    </style>

    <!-- Print JavaScript -->
    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-table', () => {
            window.print();
        });
    });
    </script>
</div>
