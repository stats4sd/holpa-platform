<?php

namespace App\Models;

use App\Models\XlsformTemplates\LanguageString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguageStringType extends Model
{

    public function languageStrings(): HasMany
    {
        return $this->hasMany(LanguageString::class);
    }
}
