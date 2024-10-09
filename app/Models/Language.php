<?php

namespace App\Models;

use App\Models\LanguageString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;

    public function languageStrings(): HasMany
    {
        return $this->hasMany(LanguageString::class);
    }
}
