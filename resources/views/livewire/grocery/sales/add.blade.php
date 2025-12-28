@php
    $productsJs = $products->map(fn($p) => ['id'=>$p->id, 'name'=>$p->name, 'sku'=>$p->sku])->values();
@endphp
<div class="p-6  bg-gray-50 min-h-screen">
    <div class=" mx-auto">
        <h1 class="text-2xl font-bold mb-2 text-slate-900">{{ __('messages.new_sale') }}</h1>
        <p class="text-slate-500">{{ __('messages.record_sale') }}</p>
        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 mt-4 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
        @endif
        <form wire:submit.prevent="save" class="card shadow-soft-xl bg-white rounded-xl mt-4 p-6 md:p-8">
            <div class="mb-5">
                <h2 class="font-semibold text-slate-700 text-base mb-2">{{ __('messages.sale_line_items') }}</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full mb-2">
                        <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                            <tr class="text-xs text-gray-500 uppercase">
                                <th class="px-3 py-2 text-left">{{ __('messages.product') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('messages.unit_price') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('messages.qty') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('messages.discount') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('messages.total') }}</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i => $item)
                            <tr>
                                <td class="px-3 py-2">
                                    <div 
                                        x-data="{
                                            open: false,
                                            query: '',
                                            selected: @entangle('items.' . $i . '.product_id'),
                                            products: [],
                                            init() { this.products = @js($productsJs); },
                                            select(id, name) { this.selected = id; this.query = ''; this.open = false; $wire.set('items.' + {{$i}} + '.product_id', id); },
                                            removeSelected() { this.selected = null; $wire.set('items.' + {{$i}} + '.product_id', null); this.open = true; },
                                            productName(id) {
                                                let product = this.products.find(p => p.id == id);
                                                return product ? product.name + ' (' + product.sku + ')' : '';
                                            }
                                        }"
                                        x-init="init()"
                                        @click.away="open = false"
                                    >
                                        <template x-if="!selected || open || query.length > 0">
                                            <div>
                                                <input 
                                                    type="text" 
                                                    placeholder="{{ __('messages.search_products') }}" 
                                                    x-model="query"
                                                    @focus="open = true"
                                                    @input="open = true"
                                                    class="rounded border-slate-300 w-full bg-white py-1 px-2 mb-1"
                                                    autocomplete="off"
                                                >
                                                <div x-show="open && query.length > 0" class="absolute z-50 mt-1 w-full bg-white border border-slate-300 rounded shadow-xl max-h-48 overflow-y-auto" style="display:none;">
                                                    <template x-for="product in products.filter(p => p.name.toLowerCase().includes(query.toLowerCase()))" :key="product.id">
                                                        <div @click="select(product.id, product.name)" class="cursor-pointer hover:bg-fuchsia-50 px-3 py-2 flex items-center gap-2">
                                                            <span x-text="product.name"></span>
                                                            <span class="text-xs text-gray-500 ml-2">(<span x-text="product.sku"></span>)</span>
                                                        </div>
                                                    </template>
                                                    <div x-show="!products.some(p => p.name.toLowerCase().includes(query.toLowerCase()))" class="px-3 py-2 text-sm text-gray-400">{{ __('messages.no_products_found') }}</div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="selected && !(open || query.length > 0)">
                                            <div class="inline-flex items-center bg-fuchsia-100 text-fuchsia-800 rounded-full px-3 py-1 text-xs mt-1">
                                                <span x-text="productName(selected)"></span>
                                                <button type="button" @click="removeSelected" class="ml-2 inline-flex items-center w-4 h-4 rounded-full hover:bg-fuchsia-200 focus:outline-none">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <input type="hidden" wire:model="items.{{$i}}.product_id">
                                    </div>
                                    @error("items.$i.product_id") <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <input wire:model="items.{{$i}}.unit_price" type="number" min="0" step="0.01" class="w-20 rounded border-slate-300 py-1 px-2 text-right" readonly>
                                    @error("items.$i.unit_price") <span class="block text-xs text-red-500">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <input wire:model.live="items.{{$i}}.quantity" type="number" min="1" class="w-14 rounded border-slate-300 py-1 px-2 text-right">
                                    @error("items.$i.quantity") <span class="block text-xs text-red-500">{{ $message }}</span> @enderror
                                    @if(isset($stockErrors[$i])) <span class="block text-xs text-red-600 italic">{{ $stockErrors[$i] }}</span> @endif
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <input wire:model.live="items.{{$i}}.discount" type="number" min="0" step="0.01" class="w-16 rounded border-slate-300 py-1 px-2 text-right">
                                    @error("items.$i.discount") <span class="block text-xs text-red-500">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <input wire:model="items.{{$i}}.total" type="number" min="0" step="0.01" class="w-24 rounded border-slate-300 py-1 px-2 text-right" readonly>
                                    @error("items.$i.total") <span class="block text-xs text-red-500">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if(count($items) > 1)
                                        <button type="button" class="text-red-500 hover:text-red-700" wire:click="removeItem({{$i}})"><i class="fas fa-minus"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" wire:click="addItem" class="bg-gradient-to-r from-purple-700 to-pink-500 text-white px-3 py-1 rounded font-medium text-xs inline-flex gap-1 items-center"><i class="fas fa-plus"></i> {{ __('messages.add_item') }}</button>
                @error('items') <span class="block mt-2 text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
            <div class="my-3 flex flex-row gap-6 items-end justify-end">
                <div>
                    <label class="block text-sm font-medium">{{ __('messages.subtotal') }}</label>
                    <div class="text-base font-semibold">Rs {{ number_format(collect($items)->sum('total'), 2) }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <label class="block text-sm font-medium mr-1">{{ __('messages.total_discount') }}</label>
                    <input wire:model.live="overall_discount" type="number" min="0" step="0.01" class="text-right rounded border border-slate-300 bg-white w-28" />
                    @error('overall_discount') <span class="block text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">{{ __('messages.grand_total') }}</label>
                    <div class="text-xl font-bold text-green-700">Rs {{ number_format($this->total_amount, 2) }}</div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block">{{ __('messages.customer') }}</label>
                        <a
                            wire:navigate
                            href="{{ localized_route('customers.add') }}"
                            class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1"
                        >
                            <i class="fas fa-plus text-xs"></i>
                            {{ __('messages.add_customer') }}
                        </a>
                    </div>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                        <select wire:model.live="customer_id" class="w-full pl-12 pr-3 py-2 rounded border border-slate-300 bg-white mt-1">
                            <option value="">{{ __('messages.select_customer') }}</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block">{{ __('messages.customer_number') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-phone"></i></span>
                        <input type="text" wire:model="customer_number" readonly class="w-full pl-12 pr-3 py-2 rounded border border-slate-300 bg-slate-50 mt-1">
                    </div>
                    <span class="text-xs text-gray-400">{{ __('messages.auto_filled_from_customer') }}</span>
                </div>
                <div>
                    <label class="block">{{ __('messages.customer_name') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                        <input type="text" wire:model="customer_name" readonly class="w-full pl-12 pr-3 py-2 rounded border border-slate-300 bg-slate-50 mt-1">
                    </div>
                    <span class="text-xs text-gray-400">{{ __('messages.auto_filled_from_customer') }}</span>
                </div>
                <div>
                    <label class="block">{{ __('messages.paid_amount') }} *</label>
                    <input type="number" wire:model="paid_amount" value="{{ $this->total_amount }}" readonly class="w-full rounded border border-slate-300 bg-white mt-1 required">
                    <span class="text-xs text-gray-400">{{ __('messages.paid_amount_auto_filled') }}</span>
                    @error('paid_amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block">{{ __('messages.payment_method') }}</label>
                    <select wire:model="payment_method" class="w-full rounded border border-slate-300 bg-white mt-1">
                        <option value="Cash">{{ __('messages.cash') }}</option>
                        <option value="Online">{{ __('messages.online') }}</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium">{{ __('messages.status') }}</label>
                    <select wire:model="status" id="status" class="w-full rounded border border-slate-300 bg-white mt-1">
                        <option value="paid">{{ __('messages.paid') }}</option>
                        <option value="unpaid">{{ __('messages.unpaid') }}</option>
                        <option value="pending">{{ __('messages.pending') }}</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                </div>
<div>
                    <label class="block">{{ __('messages.notes') }}</label>
                    <textarea wire:model="notes" rows="3" class="w-full rounded border border-slate-300 bg-white mt-1"></textarea>
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <a wire:navigate href="{{ localized_route('sales') }}" class="px-4 py-2 bg-slate-100 rounded text-slate-700">{{ __('messages.cancel') }}</a>
                <button class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 text-white rounded">{{ __('messages.save_sale') }}</button>
            </div>
        </form>
    </div>
</div>
