<?php

namespace App\Models\XlsformLanguages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Language extends Model
{

    protected static function booted()
    {
        //when a language is created, create a default locale for it
        static::created(function ($language) {
            $language->locales()->create([
                'is_default' => true
            ]);
        });
    }

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformModuleVersionLocale::class);
    }

    public function locales(): HasMany
    {
        return $this->hasMany(Locale::class);
    }

    // Does this work?
    public function defaultLocale(): HasOne
    {
        return $this->hasOne(Locale::class)->where('is_default', true);
    }
}
