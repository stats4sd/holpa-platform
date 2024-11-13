<?php

namespace App\Livewire;

use Livewire\Component;

class PageHeaderWithInstructions extends Component
{
    public $heading;
    public $instructions;
    public $videoUrl;
    
    public function render()
    {
        return view('livewire.page-header-with-instructions');
    }
}
