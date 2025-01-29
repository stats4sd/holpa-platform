<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Livewire\Component;

class PageHeaderWithInstructions extends Component
{
    public string $videoUrl;
    public string|HtmlString $instructions;
    public string|HtmlString $instructions1;
    public string|HtmlString $instructions2;
    public string|HtmlString $instructions3;
    public string|HtmlString $instructions4;
    public string|HtmlString $instructions5;
    public string|HtmlString $instructions6;
    public string|HtmlString $instructionsmarkcomplete;

    public function render(): Factory|Application|\Illuminate\Contracts\View\View|View|null
    {
        return view('livewire.page-header-with-instructions');
    }
}
