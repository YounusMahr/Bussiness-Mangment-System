<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.edit') }} {{ __('messages.categories') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.manage_categories') }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('categories') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_products') }}
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
            <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-2"></div>

            <form wire:submit.prevent="save" class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.name') }} *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-tags"></i></span>
                            <input type="text" wire:model="name" id="name" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., Fresh Produce">
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Grocery examples: Fresh Produce, Dairy, Bakery, Beverages.</p>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">Slug</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-link"></i></span>
                            <input type="text" wire:model.live="slug" id="slug" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="fresh-produce">
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Auto-generated from name. You can edit if needed.</p>
                        @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.report') }}</label>
                    <textarea wire:model="description" id="description" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Short description for this category..."></textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-slate-700 mb-2">Parent</label>
                        <input type="number" wire:model="parent_id" id="parent_id" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="Parent ID (optional)">
                        @error('parent_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
<div>
                        <label for="position" class="block text-sm font-medium text-slate-700 mb-2">Position</label>
                        <input type="number" wire:model="position" id="position" min="0" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent" placeholder="0">
                        @error('position') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center mt-6 md:mt-0">
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-purple-600 shadow-sm focus:border-fuchsia-300 focus:ring focus:ring-fuchsia-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-slate-700">{{ __('messages.active') }}</span>
                        </label>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('categories') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg">{{ __('messages.update_product') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
