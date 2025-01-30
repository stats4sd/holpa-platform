<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Livewire\Component;

class RoundedSquare extends Component
{
    public string $heading;
    public string|HtmlString $description;

    public function render(): Factory|Application|\Illuminate\Contracts\View\View|View|null
    {
        return view('livewire.rounded-square');
    }
}
