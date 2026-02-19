<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $search = "";

    public function performSearch()
    {
        if (trim($this->search)) {
            $locale = app()->getLocale();
            return $this->redirect("/{$locale}/grocery/products?search=" . urlencode($this->search), navigate: true);
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        $locale = session("locale", config("app.locale", "en"));
        return $this->redirect(route("login", ["locale" => $locale]), navigate: true);
    }

    public function render()
    {
        return view("livewire.components.navbar");
    }
}
