<?php

namespace App\Livewire;

use Illuminate\Support\HtmlString;
use Livewire\Component;

class download extends Component
{
    public string $heading;
    public string|HtmlString $description;
    public string $buttonLabel;
    public string $url;

    public function render()
    {
        return view('livewire.download');
    }
}
