<?php

namespace App\Livewire;

use Livewire\Component;

class RoundedSquare extends Component
{
    public $heading;
    public $description;
    
    public function render()
    {
        return view('livewire.rounded-square');
    }
}
