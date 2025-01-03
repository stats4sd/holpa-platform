<?php

namespace App\Models\XlsformLanguages;

use App\Models\Team;
use App\Models\Xlsforms\XlsformModuleVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locale extends Model
{

    protected $casts = [
        'is_default' => 'boolean'
    ];


    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformModuleVersionLocale::class);
    }

    public function xlsformModuleVersions(): BelongsToMany
    {
        return $this->belongsToMany(XlsformModuleVersion::class, 'xlsform_module_version_locale', 'locale_id', 'xlsform_module_version_id')
            ->using(XlsformModuleVersionLocale::class)
            ->withPivot(['has_language_strings', 'needs_update']);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'locale_team', 'locale_id', 'team_id');
    }

    public function getLanguageLabelAttribute(): string
    {
        $language = $this->language->name;
        $isoAlpha2 = $this->language->iso_alpha2;
        $description = $this->description ? ' - ' . $this->description : '';

        return $language . ' (' . $isoAlpha2 . ')' . $description;
    }

    public function getStatusAttribute(): string
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

    public function getOdkLabelAttribute(): string
    {
        return $this->language->name . ' (' . $this->language->iso_alpha2 . ')';
    }

}
