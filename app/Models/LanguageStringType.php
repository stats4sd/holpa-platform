<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageStringType extends Model
{
    use HasFactory;

    public function languageStrings(): HasMany
    {
        return $this->hasMany(LanguageString::class);
    }
}
