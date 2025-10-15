<?php

namespace App\Livewire;

use Livewire\Component;

class Changelog extends Component
{
    public string $tab = 'app';

    public function render()
    {
        return view('livewire.changelog');
    }
}
