<?php

namespace App\Livewire;

use Livewire\Component;

class RoundedSection extends Component
{
    public $heading;
    public $description;
    public $buttonLabel;
    public $url;
    
    public function render()
    {
        return view('livewire.rounded-section');
    }
}
