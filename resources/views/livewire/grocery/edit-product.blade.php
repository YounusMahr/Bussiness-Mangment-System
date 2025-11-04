<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Edit Product</h1>
                    <p class="text-slate-600 mt-1">Update the product details</p>
                </div>
                <a wire:navigate href="{{ localized_route('products') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    Back to Products
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-soft-xl overflow-hidden">
            <!-- Card header bar -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <!-- Product basics -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Product Details</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Product Name *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-box"></i></span>
                                <input type="text" wire:model="name" id="name" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., Wireless Headphones">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="sku" class="block text-sm font-medium text-slate-700 mb-2">SKU *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-barcode"></i></span>
                                <input type="text" wire:model="sku" id="sku" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400 uppercase tracking-wide" placeholder="PRD-001">
                            </div>
                            @error('sku') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 flex flex-col md:items-start">
                    <label class="w-24 h-24 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 cursor-pointer bg-gray-50 hover:bg-gray-100 relative">
                        @if ($image)
                            <img src="{{ $image instanceof \Livewire\TemporaryUploadedFile ? $image->temporaryUrl() : asset('storage/'.$image) }}" class="w-full h-full object-cover rounded-lg" />
                        @else
                            <span class="text-gray-400 text-3xl"><i class="fas fa-image"></i></span>
                        @endif
                        <input type="file" wire:model="image" accept="image/*" class="hidden" />
                        @if ($image && $image instanceof \Livewire\TemporaryUploadedFile)
                            <button type="button" wire:click="removeImage" class="absolute -top-2 -right-2 bg-white border border-gray-300 rounded-full p-1 text-xs text-gray-600 hover:text-red-600"><i class="fas fa-times"></i></button>
                        @endif
                    </label>
                    <span class="text-xs text-gray-500 mt-2">Product image (optional)</span>
                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Inventory & pricing -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Inventory & Pricing</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-slate-700 mb-2">Quantity *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-layer-group"></i></span>
                                <input type="number" wire:model="quantity" id="quantity" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('quantity') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-slate-700 mb-2">Price *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400">$</span>
                                <input type="number" wire:model="price" id="price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                            </div>
                            @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
<div>
                            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                            <select wire:model="category_id" id="category_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Choose a grocery category. <a wire:navigate href='{{ route('categories.add') }}' class='text-blue-600 underline'>Add new</a> if needed.</p>
                            @if(count($categories) == 0)
                                <p class="text-xs text-red-600 mt-1">No categories exist. Please <a wire:navigate href='{{ route('categories.add') }}' class='underline'>add one</a> first.</p>
                            @endif
                            @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Description</h2>
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Details</label>
                        <textarea wire:model="description" id="description" rows="5" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Write a clear and concise description..."></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-purple-600 shadow-sm focus:border-fuchsia-300 focus:ring focus:ring-fuchsia-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-slate-700">Product is active</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('products') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
