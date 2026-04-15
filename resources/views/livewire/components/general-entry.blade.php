<div x-data="{ open: @entangle('isOpen') }" 
     x-show="open" 
     class="fixed inset-0 z-[9999] overflow-y-auto" 
     x-cloak>
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
         @click="open = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-2xl bg-white rounded-3xl shadow-soft-3xl overflow-hidden z-[10000]">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-700 to-pink-500 p-6 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    {{ __('Entry Form') }}
                </h3>
                <button @click="open = false" class="text-white/80 hover:text-white">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date *</label>
                        <input type="date" wire:model="date" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-fuchsia-400 outline-none">
                        @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Category *</label>
                        <select wire:model.live="category" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-fuchsia-400 outline-none">
                            <option value="">Select Category</option>
                            <option value="udhaar">Khata (Grocery)</option>
                            <option value="credit">Credit (Invest Cash)</option>
                            <option value="installments">Installments (Vehicle)</option>
                            <option value="plot_sale">Plot Sale</option>
                            <option value="plot_purchase">Plot Purchase</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Customer Search -->
                    <div class="relative md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Customer Selection *</label>
                        <div class="flex gap-2">
                            <div class="relative flex-grow">
                                <input type="text" 
                                       wire:model.live="search_customer" 
                                       placeholder="Search by name or number..."
                                       class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-fuchsia-400 outline-none">
                                
                                @if(!empty($customer_results))
                                    <div class="absolute z-[100] w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg overflow-hidden">
                                        @forelse($customer_results as $customer)
                                            <button type="button" 
                                                    wire:click="selectCustomer({{ $customer->id }}, '{{ $customer->name }}')"
                                                    class="w-full text-left px-4 py-3 hover:bg-slate-50 transition-colors border-b last:border-0">
                                                <div class="font-semibold text-slate-700">{{ $customer->name }}</div>
                                                <div class="text-xs text-slate-400">{{ $customer->number }} | {{ $customer->type }}</div>
                                            </button>
                                        @empty
                                            <div class="px-4 py-3 text-sm text-slate-500 text-center italic">
                                                No users found in this category
                                            </div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                            <button type="button" 
                                    @click="$wire.set('is_creating_customer', !@js($is_creating_customer))"
                                    class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Quick Add Customer Form -->
                    @if($is_creating_customer)
                    <div class="md:col-span-2 p-5 bg-slate-50 rounded-2xl border border-slate-200 shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Quick Add Customer</h4>
                            <button type="button" @click="$wire.set('is_creating_customer', false)" class="text-slate-400 hover:text-slate-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1 ml-1">Full Name *</label>
                                <input type="text" wire:model="new_customer_name" placeholder="Name" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-fuchsia-400 bg-white">
                                @error('new_customer_name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1 ml-1">Phone Number *</label>
                                <input type="text" wire:model="new_customer_number" placeholder="Phone" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-fuchsia-400 bg-white">
                                @error('new_customer_number') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1 ml-1">Email Address</label>
                                <input type="email" wire:model="new_customer_email" placeholder="email@example.com" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-fuchsia-400 bg-white">
                                @error('new_customer_email') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Image -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1 ml-1">Profile Image</label>
                                <div class="flex items-center gap-3">
                                    <div class="relative group">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                                            @if ($new_customer_image)
                                                <img src="{{ $new_customer_image->temporaryUrl() }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-user text-slate-400"></i>
                                            @endif
                                        </div>
                                        @if ($new_customer_image)
                                            <button type="button" wire:click="$set('new_customer_image', null)" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[8px] opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <input type="file" wire:model="new_customer_image" class="w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-fuchsia-50 file:text-fuchsia-700 hover:file:bg-fuchsia-100 transition-all cursor-pointer">
                                    </div>
                                </div>
                                <div wire:loading wire:target="new_customer_image" class="text-[10px] text-fuchsia-500 mt-1">Uploading...</div>
                                @error('new_customer_image') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 flex justify-end mt-2 pt-3 border-t border-slate-100">
                                <button type="button" wire:click="createCustomer" class="px-5 py-2 bg-gradient-to-r from-red-600 to-pink-500 hover:from-red-700 hover:to-pink-600 text-white rounded-xl text-sm font-bold shadow-soft-md transition-all active:scale-95">
                                    <i class="fas fa-user-plus mr-1"></i> Confirm Customer
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Operation -->
                    @if($category)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Operation *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" wire:click="$set('operation', 'create')" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $operation === 'create' ? 'bg-fuchsia-100 text-fuchsia-700 border-2 border-fuchsia-500' : 'bg-slate-100 text-slate-700 border-2 border-transparent' }}">Initial Entry</button>
                            <button type="button" wire:click="$set('operation', 'credit')" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $operation === 'credit' ? 'bg-green-100 text-green-700 border-2 border-green-500' : 'bg-slate-100 text-slate-700 border-2 border-transparent' }}">Credit (In)</button>
                            <button type="button" wire:click="$set('operation', 'debit')" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $operation === 'debit' ? 'bg-red-100 text-red-700 border-2 border-red-500' : 'bg-slate-100 text-slate-700 border-2 border-transparent' }}">Debit (Out)</button>
                        </div>
                        @error('operation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Record Selection (for Credit/Debit) -->
                    @if($operation && $operation !== 'create' && !empty($records))
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select Account/Record *</label>
                        <select wire:model.live="record_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-fuchsia-400 outline-none">
                            <option value="">Select an active record...</option>
                            @foreach($records as $rec)
                                <option value="{{ $rec->id }}">
                                    @if($category === 'udhaar') ID: {{ $rec->id }} | Rem: {{ $rec->remaining_amount }}
                                    @elseif($category === 'installments') {{ $rec->vehicle }} | Rem: {{ $rec->remaining }}
                                    @elseif($category === 'plot_sale') Plot Sales ID: {{ $rec->id }} 
                                    @elseif($category === 'plot_purchase') Plot Purchase ID: {{ $rec->id }}
                                    @elseif($category === 'credit') Transaction ID: {{ $rec->id }} | Inv: {{ $rec->invest_cash }} | Date: {{ $rec->date->format('d/m/Y') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('record_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Dynamic Fields -->
                    @if($operation)
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                            @if($operation === 'create')
                                <!-- Create Fields -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Total Amount / Value *</label>
                                    <input type="number" wire:model.live="total_amount" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                    @error('total_amount') <span class="text-red-500 text-xs mt-1 text-block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Initial Paid *</label>
                                    <input type="number" wire:model.live="paid_amount" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                    @error('paid_amount') <span class="text-red-500 text-xs mt-1 text-block">{{ $message }}</span> @enderror
                                </div>
                                
                                @if($category === 'udhaar')
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Product (optional)</label>
                                    <select wire:model="product_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                        <option value="">None</option>
                                        @foreach($products as $p) <option value="{{ $p->id }}">{{ $p->name }}</option> @endforeach
                                    </select>
                                </div>
                                @endif

                                @if($category === 'installments')
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Vehicle *</label>
                                    <input type="text" wire:model="vehicle" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                    @error('vehicle') <span class="text-red-500 text-xs mt-1 text-block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Model</label>
                                    <input type="text" wire:model="model" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                </div>
                                @endif

                                @if($category === 'plot_sale')
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Plot (Purchase Record) *</label>
                                    <select wire:model="plot_purchase_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                        <option value="">Select Plot...</option>
                                        @foreach($plotPurchases as $plot)
                                            <option value="{{ $plot->id }}">{{ $plot->location }} - {{ $plot->plot_area }} (ID: {{ $plot->id }})</option>
                                        @endforeach
                                    </select>
                                    @error('plot_purchase_id') <span class="text-red-500 text-xs mt-1 text-block">{{ $message }}</span> @enderror
                                </div>
                                @endif

                                @if($category === 'plot_purchase' || $category === 'plot_sale')
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Plot Area</label>
                                    <input type="text" wire:model="plot_area" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Location</label>
                                    <input type="text" wire:model="location" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Interest/Markup</label>
                                    <input type="number" wire:model.live="interest" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Time Period</label>
                                    <input type="text" wire:model="time_period" placeholder="e.g., 6 months" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Due Date</label>
                                    <input type="date" wire:model="due_date" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none">
                                </div>
                            @else
                                <!-- Transaction Fields -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Transaction Amount *</label>
                                    <input type="number" wire:model="amount" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    @error('amount') <span class="text-red-500 text-xs mt-1 text-block">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Notes</label>
                                <textarea wire:model="notes" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-fuchsia-400"></textarea>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                    <button type="button" @click="open = false" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg font-semibold hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-700 to-pink-500 text-white rounded-lg font-bold shadow-soft-md hover:scale-102 transition-all">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
