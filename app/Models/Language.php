<?php

namespace App\Models;

use App\Models\Xlsforms\XlsformTemplateLanguage;
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
