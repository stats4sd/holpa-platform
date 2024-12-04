<?php

namespace App\Livewire;

use Illuminate\Support\HtmlString;
use Livewire\Component;

class PageHeaderWithInstructions extends Component
{
    public string $videoUrl;
    public string|HtmlString $instructions;

    public function render()
    {
        return view('livewire.page-header-with-instructions');
    }
}
