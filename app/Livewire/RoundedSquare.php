<?php

namespace App\Livewire;

use Illuminate\Support\HtmlString;
use Livewire\Component;

class RoundedSquare extends Component
{
    public string $heading;
    public string|HtmlString $description;

    public function render()
    {
        return view('livewire.rounded-square');
    }
}
