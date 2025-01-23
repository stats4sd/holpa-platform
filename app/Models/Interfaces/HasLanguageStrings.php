<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasLanguageStrings
{
    public function languageStrings(): MorphMany;
}
