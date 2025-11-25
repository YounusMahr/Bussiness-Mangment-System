<div class="p-6">
    <div class=" mx-auto">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    <p class="text-green-700 font-medium">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- Edit User Form -->
        @if($editingUser)
            <div class="bg-white shadow-soft-xl rounded-2xl overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-purple-700 to-pink-500 px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full text-white font-bold text-lg border-2 border-white/30">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Edit Profile</h1>
                            <p class="text-white/80 text-sm mt-0.5">Update your account information</p>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <div class="p-8">
                    <form wire:submit.prevent="save">
                        <div class="space-y-6">
                            <!-- User Image -->
                            <div x-data="{ preview: null }">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-image text-purple-600 mr-2"></i>
                                    Profile Image
                                    <span class="text-xs font-normal text-gray-500 ml-1">(optional)</span>
                                </label>
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
                                                @elseif($oldImage)
                                                    <img src="{{ asset('storage/'.$oldImage) }}" class="w-full h-full object-cover rounded-lg" alt="Current Image" />
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
                                        @if (($image && $image instanceof \Livewire\TemporaryUploadedFile) || $oldImage)
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
                                    <span class="text-xs text-slate-500 mt-2">Click to upload profile image (Max: 2MB)</span>
                                    @error('image') 
                                        <span class="text-red-500 text-xs mt-1 block flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </span> 
                                    @enderror
                                    <div wire:loading wire:target="image" class="text-xs text-blue-500 mt-1">
                                        <i class="fas fa-spinner fa-spin"></i> Uploading...
                                    </div>
                                </div>
                            </div>

                            <!-- Name Field -->
                            <div>
                                <label for="edit_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-purple-600 mr-2"></i>
                                    Full Name
                                    <span class="text-xs font-normal text-gray-500 ml-1">(optional)</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="name"
                                    id="edit_name"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 bg-slate-50 hover:bg-white"
                                    placeholder="Enter your full name"
                                >
                                @error('name') 
                                    <span class="text-red-500 text-xs mt-1.5 block flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </span> 
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="edit_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-purple-600 mr-2"></i>
                                    Email Address
                                    <span class="text-xs font-normal text-gray-500 ml-1">(optional)</span>
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="email"
                                    id="edit_email"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 bg-slate-50 hover:bg-white"
                                    placeholder="Enter your email address"
                                >
                                @error('email') 
                                    <span class="text-red-500 text-xs mt-1.5 block flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </span> 
                                @enderror
                            </div>

                            <!-- Password Fields Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password Field -->
                                <div>
                                    <label for="edit_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-lock text-purple-600 mr-2"></i>
                                        New Password
                                        <span class="text-xs font-normal text-gray-500 ml-1">(optional)</span>
                                    </label>
                                    <input 
                                        type="password" 
                                        wire:model="password"
                                        id="edit_password"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 bg-slate-50 hover:bg-white"
                                        placeholder="Enter new password"
                                    >
                                    @error('password') 
                                        <span class="text-red-500 text-xs mt-1.5 block flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </span> 
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                        <i class="fas fa-info-circle text-purple-500"></i>
                                        Leave blank to keep current password
                                    </p>
                                </div>

                                <!-- Password Confirmation (only show if password is entered) -->
                                @if($password)
                                    <div>
                                        <label for="edit_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <i class="fas fa-lock text-purple-600 mr-2"></i>
                                            Confirm Password
                                            <span class="text-xs font-normal text-gray-500 ml-1">(required if password is set)</span>
                                        </label>
                                        <input 
                                            type="password" 
                                            wire:model="password_confirmation"
                                            id="edit_password_confirmation"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder:text-slate-400 bg-slate-50 hover:bg-white"
                                            placeholder="Confirm new password"
                                        >
                                        @error('password_confirmation') 
                                            <span class="text-red-500 text-xs mt-1.5 block flex items-center gap-1">
                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                            </span> 
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-8 pt-6 border-t border-slate-200">
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                <span wire:loading.remove wire:target="save">
                                    <i class="fas fa-save"></i>
                                    Update Profile
                                </span>
                                <span wire:loading wire:target="save" class="flex items-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white shadow-soft-xl rounded-2xl p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-100 to-pink-100 rounded-full mb-4">
                    <i class="fas fa-user-circle text-4xl text-purple-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Profile Found</h3>
                <p class="text-gray-500">Please log in to view your profile</p>
            </div>
        @endif
    </div>
</div>
