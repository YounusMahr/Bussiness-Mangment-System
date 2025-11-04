<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
<div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.categories') }}</h1>
                </div>
                <div class="flex justify-between w-full">
                    <p class="text-gray-600 mt-1">{{ __('messages.manage_categories') }}</p>
                    <a 
                        wire:navigate
                        href="{{ localized_route('categories.add') }}" 
                        class="bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white md:font-bold font-normal py-1 px-2 md:py-2 md:px-2 rounded-lg flex items-center gap-2"
                    >
                        <i class="fas fa-plus"></i>
                        {{ __('messages.add_category') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Search -->
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
                            placeholder="{{ __('messages.search_categories') }}"
                            class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-full leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                        <tr>
                            <th wire:click="sortBy('name')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-2">
                                    {{ __('messages.name') }}
                                </div>
                            </th>
                            <th wire:click="sortBy('slug')" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    @if($category->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($category->description, 60) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $category->slug }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                        {{ $category->is_active ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a wire:navigate href="{{ localized_route('categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900" title="{{ __('messages.edit') }}"><i class="fas fa-edit"></i></a>
                                        <button wire:click="confirmDelete({{ $category->id }})" class="text-red-600 hover:text-red-900" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">{{ __('messages.no_categories_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
                <div class="flex justify-center py-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-b-2xl shadow-inner mt-2">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
@if($confirmingDeleteId)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-white/30 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-lg max-w-sm w-full p-8 text-center animate-fade-in">
            <div class="mb-5">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </span>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Delete Category?</h3>
                <p class="text-slate-600 text-sm">Are you sure you want to delete this category? This action cannot be undone.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row justify-center items-center mt-6">
                <button wire:click="delete({{ $confirmingDeleteId }})" class="px-5 py-2 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-red-300 transition">Yes, Delete</button>
                <button wire:click="cancelDelete" class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-slate-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300 transition">Cancel</button>
            </div>
        </div>
    </div>
@endif
