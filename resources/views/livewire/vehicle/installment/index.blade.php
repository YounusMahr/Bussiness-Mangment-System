<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex flex-col lg:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('installments') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('manage_installment_records') }}</p>
            </div>
            <div>
                <a 
                    wire:navigate
                    href="{{ localized_route('vehicle.installment.add') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-medium rounded-lg shadow-md transition-colors"
                >
                    <i class="fas fa-plus"></i>
                    {{ __('add_installment') }}
                </a>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
        @endif

        <div class="bg-white shadow-soft-xl rounded-2xl p-4 mb-6">
            <div class="flex gap-4 items-center md:justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <span class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center rounded-lg py-2 px-2.5 text-center font-normal text-slate-500"><i class="fas fa-search"></i></span>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="{{ __('search_installments') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline w-full rounded-lg border border-gray-300 bg-white py-2 pr-3 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Installments Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($installments as $installment)
                <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-500 h-2"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-4 mb-4">
                            <!-- Customer Image/Avatar -->
                            <div class="flex-shrink-0">
                                @if($installment->customer && $installment->customer->image)
                                    <img src="{{ asset('storage/'.$installment->customer->image) }}" alt="{{ $installment->customer->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-blue-200">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-indigo-400 flex items-center justify-center border-2 border-blue-200">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Customer Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $installment->customer->name ?? 'N/A' }}
                                </h3>
                                <p class="text-sm text-gray-600 flex items-center gap-1 mt-1">
                                    <i class="fas fa-phone text-xs"></i>
                                    {{ $installment->number ?? $installment->customer->number ?? '--' }}
                                </p>
                            </div>
                        </div>

                        <!-- Installment Details -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('vehicle') }}:</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $installment->vehicle ?? '--' }} 
                                    @if($installment->model)
                                        <span class="text-gray-500">({{ $installment->model }})</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('total_price') }}:</span>
                                <span class="text-sm font-semibold text-indigo-600">Rs {{ number_format($installment->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('remaining') }}:</span>
                                <span class="text-sm font-semibold text-red-600">Rs {{ number_format($installment->remaining, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ __('date') }}:</span>
                                <span class="text-sm text-gray-700">{{ $installment->date->format('Y-m-d') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="border-t border-gray-200 pt-4 mt-4 flex flex-wrap gap-2">
                            <a
                                wire:navigate
                                href="{{ localized_route('vehicle.installment.install-add', $installment) }}"
                                class="flex-1 min-w-[80px] text-center px-3 py-2 text-xs font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm"
                                title="{{ __('add') }}"
                            >
                                <i class="fas fa-plus mr-1"></i>
                                {{ __('add') }}
                            </a>
                            <a
                                wire:navigate
                                href="{{ localized_route('vehicle.installment.return', $installment) }}"
                                class="flex-1 min-w-[80px] text-center px-3 py-2 text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors shadow-sm"
                                title="{{ __('return') }}"
                            >
                                <i class="fas fa-arrow-down mr-1"></i>
                                {{ __('return') }}
                            </a>
                            <a
                                wire:navigate
                                href="{{ localized_route('vehicle.installment.history', $installment) }}"
                                class="flex-1 min-w-[80px] text-center px-3 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm"
                                title="{{ __('history') }}"
                            >
                                <i class="fas fa-history mr-1"></i>
                                {{ __('history') }}
                            </a>
                            <a
                                wire:navigate
                                href="{{ localized_route('vehicle.installment.edit', $installment) }}"
                                class="flex-1 min-w-[80px] text-center px-3 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-sm"
                                title="{{ __('edit') }}"
                            >
                                <i class="fas fa-edit mr-1"></i>
                                {{ __('edit') }}
                            </a>
                            <button 
                                wire:click="confirmDelete({{ $installment->id }})"
                                class="flex-1 min-w-[80px] text-center px-3 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm"
                                title="{{ __('delete') }}"
                            >
                                <i class="fas fa-trash mr-1"></i>
                                {{ __('delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white shadow-soft-xl rounded-2xl p-12 text-center">
                        <i class="fas fa-car text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">{{ __('no_installments_found') }}</h3>
                        <p class="text-gray-500 mb-6">{{ __('get_started_by_creating_first_installment') }}</p>
                        <a wire:navigate href="{{ localized_route('vehicle.installment.add') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-semibold rounded-lg shadow-md transition-colors">
                            <i class="fas fa-plus"></i>
                            {{ __('add_installment') }}
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($installments->hasPages())
            <div class="flex justify-center py-6 mt-6">
                {{ $installments->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeleteId)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
                <div class="mb-5">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </span>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('delete_installment') }}?</h3>
                    <p class="text-slate-600 text-sm">{{ __('are_you_sure_delete_installment') }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                    <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">{{ __('yes_delete') }}</button>
                    <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">{{ __('cancel') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
