<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasLanguageStrings
{
    public function languageStrings(): MorphMany;
}
