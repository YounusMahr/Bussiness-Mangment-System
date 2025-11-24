<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.add_customer') }}</h1>
                    <p class="text-slate-600 mt-1">{{ __('messages.create_new_customer') }}</p>
                </div>
                <a wire:navigate href="{{ localized_route('customers.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.back_to_customers') }}
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
                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">{{ __('messages.customer_information') }}</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.customer_name') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-user"></i></span>
                                <input type="text" wire:model="name" id="name" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Enter customer name">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="number" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.customer_number') }} *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-phone"></i></span>
                                <input type="text" wire:model="number" id="number" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="Enter phone number">
                            </div>
                            @error('number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.email') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-envelope"></i></span>
                                <input type="email" wire:model="email" id="email" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="customer@example.com">
                            </div>
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.type') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-tag"></i></span>
                                <input type="text" value="Grocery" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600" readonly>
                            </div>
                            <input type="hidden" wire:model="type" value="Grocery">
                            <p class="mt-1 text-xs text-slate-500">{{ __('messages.customer_type_grocery') }}</p>
                            @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Customer Image -->
                <div class="mb-8" x-data="{ preview: null }">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">{{ __('messages.customer_image') }}</h2>
                    <div class="flex flex-col md:items-start">
                        <label class="w-32 h-32 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 cursor-pointer bg-gray-50 hover:bg-gray-100 relative overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center">
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover rounded-lg" alt="Preview" />
                                </template>
                                <template x-if="!preview">
                                    @if ($image)
                                        @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover rounded-lg" alt="Preview" />
                                        @elseif(is_string($image))
                                            <img src="{{ asset('storage/'.$image) }}" class="w-full h-full object-cover rounded-lg" alt="Preview" />
                                        @else
                                            <span class="text-gray-400 text-4xl"><i class="fas fa-user-circle"></i></span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-4xl"><i class="fas fa-user-circle"></i></span>
                                    @endif
                                </template>
                            </div>
                            <input 
                                type="file" 
                                wire:model="image" 
                                accept="image/*" 
                                class="hidden" 
                                x-on:change="
                                    if ($event.target.files && $event.target.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { preview = e.target.result; };
                                        reader.readAsDataURL($event.target.files[0]);
                                    }
                                "
                            />
                            @if ($image)
                                <button 
                                    type="button" 
                                    wire:click="removeImage" 
                                    x-on:click="preview = null"
                                    class="absolute -top-2 -right-2 bg-white border border-gray-300 rounded-full p-1.5 text-xs text-gray-600 hover:text-red-600 shadow-sm z-10"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </label>
                        <span class="text-xs text-slate-500 mt-2">{{ __('messages.customer_image_optional') }}</span>
                        @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="image" class="text-xs text-blue-500 mt-1">
                            <i class="fas fa-spinner fa-spin"></i> Uploading...
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">{{ __('messages.address') }}</h2>
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-2">{{ __('messages.address') }}</label>
                        <textarea wire:model="address" id="address" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="{{ __('messages.enter_customer_address') }}"></textarea>
                        @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('customers.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg">{{ __('messages.save_customer') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
