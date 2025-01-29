<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasLanguageStrings
{
    public function languageStrings(): MorphMany;
}
