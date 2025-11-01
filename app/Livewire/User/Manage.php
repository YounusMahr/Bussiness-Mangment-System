<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class Manage extends Component
{
    public $editingUser = null;
    
    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->editingUser = $user;
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    protected function rules(): array
    {
        $emailRule = ['required', 'string', 'email', 'max:255'];
        
        if ($this->editingUser) {
            $emailRule[] = 'unique:users,email,' . $this->editingUser->id;
        }
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRule,
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function save()
    {
        $this->validate($this->rules());

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // Only update password if it's provided
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingUser) {
            $this->editingUser->update($data);
            session()->flash('message', 'Profile updated successfully!');
            // Reload the user to show updated data
            $this->editingUser->refresh();
            $this->name = $this->editingUser->name;
            $this->email = $this->editingUser->email;
            $this->password = '';
            $this->password_confirmation = '';
        }
    }

    public function render()
    {
        return view('livewire.user.manage');
    }
}

