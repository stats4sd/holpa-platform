<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use Livewire\Component;

class TeamLanguageRow extends Component
{

    public Team $team;
    public Language $language;

    public function render()
    {
        return view('livewire.team-language-row');
    }
}
