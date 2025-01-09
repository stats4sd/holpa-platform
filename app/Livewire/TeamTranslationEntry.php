<?php

namespace App\Livewire;

use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use Livewire\Component;

class TeamTranslationEntry extends Component
{

    public Team $team;
    public Language $language;

    public bool $expanded = false;

    public function render()
    {
        return view('livewire.team-translation-entry');
    }


}
