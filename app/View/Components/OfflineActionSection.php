<?php

namespace App\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Illuminate\View\Component;

class OfflineActionSection extends Component
{
    public string $heading = '';
    public string|HtmlString $description = '';
    public string $buttonLabel = '';
    public string $url = '';

    public function render(): Factory|Application|\Illuminate\Contracts\View\View|View|null
    {
        return view('components.offline-action-section');
    }
}
