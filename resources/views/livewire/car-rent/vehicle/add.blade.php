<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Add New Vehicle</h1>
                    <p class="text-slate-600 mt-1">Create a vehicle for your fleet</p>
                </div>
                <a wire:navigate href="{{ localized_route('vehicles.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
                    <i class="fas fa-arrow-left"></i>
                    Back to Vehicles
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
                <!-- Vehicle basics -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Vehicle Details</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="vehicle_name" class="block text-sm font-medium text-slate-700 mb-2">Vehicle Name *</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-car"></i></span>
                                <input type="text" wire:model="Vehicle_name" id="vehicle_name" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., Toyota Corolla">
                            </div>
                            @error('Vehicle_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="model" class="block text-sm font-medium text-slate-700 mb-2">Model</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-id-card"></i></span>
                                <input type="text" wire:model="model" id="model" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="e.g., 2020 S">
                            </div>
                            @error('model') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="rent_price" class="block text-sm font-medium text-slate-700 mb-2">Rent Price</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fas fa-dollar-sign"></i></span>
                                <input type="number" wire:model="rent_price" id="rent_price" step="0.01" min="0" class="w-full pl-12 pr-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent placeholder:text-slate-400" placeholder="0.00">
                            </div>
                            @error('rent_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                    <span class="text-xs text-gray-500 mt-2">Vehicle image (optional)</span>
                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider">Status</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Availability</label>
                            <select wire:model="status" id="status" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent">
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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

                <!-- Active -->
                <div class="mb-8">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="is_active" class="rounded border-slate-300 text-purple-600 shadow-sm focus:border-fuchsia-300 focus:ring focus:ring-fuchsia-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-slate-700">Vehicle is active</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="pt-2 flex items-center justify-end gap-3">
                    <a wire:navigate href="{{ localized_route('vehicles.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white rounded-lg">Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>
