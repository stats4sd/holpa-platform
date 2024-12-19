<?php

namespace App\View\Components;

use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class RoundedSection extends Component
{


    public function __construct(
        public ?string                $heading,
        public string|HtmlString|null $description,
        public ?string                $buttonLabel,
        public ?string                $url
    )
    {
    }

    public
    function render()
    {
        return view('components.rounded-section');
    }
}
