<?php

namespace App\Models;

use App\Models\Xlsforms\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locale extends Model
{

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'locale_team', 'locale_id', 'team_id');
    }

    public function getLanguageLabelAttribute()
    {
        $language = $this->language->name;
        $isoAlpha2 = $this->language->iso_alpha2;
        $description = $this->description ? ' - ' . $this->description : '';

        return $language . ' (' . $isoAlpha2 . ')' . $description;
    }

    public function getStatusAttribute()
    {
        $statuses = $this->xlsformTemplateLanguages->pluck('status');

        if ($statuses->contains('Not added')) {
            return 'Not added';
        }

        if ($statuses->contains('Out of date')) {
            return 'Out of date';
        }

        if ($statuses->every(fn($status) => $status === 'Ready for use')) {
            return 'Ready for use';
        }
    }

    public function getOdkLabelAttribute()
    {
        return $this->language->name . ' (' . $this->language->iso_alpha2 . ')';
    }

}
