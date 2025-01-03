<?php

namespace App\Models\XlsformLanguages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }

    public function locales(): HasMany
    {
        return $this->hasMany(Locale::class);
    }
}
