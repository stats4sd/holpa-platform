<?php

namespace App\Livewire;

use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class SubmissionView extends Component
{

    public Submission $submission;

    public function render()
    {
        return view('livewire.submission-view');
    }
}
