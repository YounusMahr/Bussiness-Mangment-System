<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Validation\ValidationException;
#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function login(): void
    {
        $credentials = $this->validate();

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        session()->regenerate();
        $this->redirectIntended(route('index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.auth.login')
            ->title('Login');
    }
}
