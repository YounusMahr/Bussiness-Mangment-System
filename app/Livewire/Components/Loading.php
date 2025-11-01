<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Loading extends Component
{
    public $loading = false;
    public $loadingText = 'Loading...';
    public $loadingType = 'default';

    protected $listeners = ['show-loading', 'hide-loading'];

    public function showLoading($text = 'Loading...', $type = 'default')
    {
        $this->loading = true;
        $this->loadingText = $text;
        $this->loadingType = $type;
    }

    public function hideLoading()
    {
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.components.loading');
    }
}
