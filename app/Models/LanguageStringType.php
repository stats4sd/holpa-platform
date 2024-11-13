<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LanguageStringType extends Model
{

    public function languageStrings(): HasMany
    {
        return $this->hasMany(LanguageString::class);
    }
}
