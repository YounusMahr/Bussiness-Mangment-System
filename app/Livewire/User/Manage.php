<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class Manage extends Component
{
    use WithFileUploads;
    
    public $editingUser = null;
    
    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $image;
    public $oldImage;
    public $imageRemoved = false;

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->editingUser = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->oldImage = $user->image;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
        
        // Email validation - only validate if provided
        if (!empty($this->email)) {
            $emailRule = ['required', 'string', 'email', 'max:255'];
            if ($this->editingUser) {
                $emailRule[] = 'unique:users,email,' . $this->editingUser->id;
            }
            $rules['email'] = $emailRule;
        } else {
            $rules['email'] = ['nullable', 'string', 'email', 'max:255'];
        }
        
        // Password validation - only validate if provided
        if (!empty($this->password)) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['password_confirmation'] = ['required', 'string', 'min:8'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8'];
            $rules['password_confirmation'] = ['nullable', 'string'];
        }
        
        return $rules;
    }

    public function removeImage()
    {
        $this->image = null;
        if ($this->oldImage) {
            Storage::disk('public')->delete($this->oldImage);
            $this->oldImage = null;
            $this->imageRemoved = true;
        }
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [];

        // Update name if provided, otherwise keep existing
        if (trim($this->name) !== '') {
            $data['name'] = trim($this->name);
        } else {
            // Keep existing name if not provided
            $data['name'] = $this->editingUser->name;
        }

        // Update email if provided, otherwise keep existing
        if (trim($this->email) !== '') {
            $data['email'] = trim($this->email);
        } else {
            // Keep existing email if not provided
            $data['email'] = $this->editingUser->email;
        }

        // Only update password if it's provided
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // Handle image upload
        if ($this->image) {
            // Delete old image if exists
            if ($this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $data['image'] = $this->image->store('users', 'public');
        } elseif ($this->imageRemoved) {
            // If image was removed, set to null
            $data['image'] = null;
        } else {
            // Preserve existing image if not changed
            if ($this->oldImage) {
                $data['image'] = $this->oldImage;
            }
        }

        if ($this->editingUser) {
            $this->editingUser->update($data);
            session()->flash('message', 'Profile updated successfully!');
            // Reload the user to show updated data
            $this->editingUser->refresh();
            // Refresh the authenticated user in the session
            Auth::setUser($this->editingUser);
            $this->name = $this->editingUser->name;
            $this->email = $this->editingUser->email;
            $this->oldImage = $this->editingUser->image;
            $this->image = null;
            $this->imageRemoved = false;
            $this->password = '';
            $this->password_confirmation = '';
        }
    }

    public function render()
    {
        return view('livewire.user.manage');
    }
}

