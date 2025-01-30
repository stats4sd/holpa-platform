<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class CoverPage extends Component
{
    public function render(): Factory|Application|View|\Illuminate\View\View|null
    {
        return view('livewire.cover-page');
    }
}
