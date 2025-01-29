<?php

namespace App\Models\Xlsforms;

use App\Models\XlsformLanguages\Locale;
use App\Models\XlsformLanguages\XlsformModuleVersionLocale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class XlsformModule extends Model
{

    use HasRelationships;

    protected $table = 'xlsform_modules';

    protected static function booted(): void
    {

        // when a module is created, create a default Global version for it:
        static::created(function (self $module) {

            // make sure xlsformModuleVersion exists, so we can import the survey rows etc
            $module->xlsformModuleVersions()->firstOrCreate([
                'name' => 'Global ' . $module->name, // hard-code name for now
                'is_default' => true,
            ]);
        });

    }

    public function xlsformModuleVersions(): HasMany
    {
        return $this->hasMany(XlsformModuleVersion::class);
    }

    public function defaultXlsformVersion(): HasOne
    {
        return $this->hasOne(XlsformModuleVersion::class)->where('is_default', true);
    }

    // In a generalised version, this might be a BelongsToMany/MorphToMany, so we can use the same module (e.g. "diet diversity") in different templates.
    // That will come when we make it so that users can start to make their own templates.

    // Links to *either* XlsformTemplate (for standard / global modules) or Xlsform (for custom modules uploaded by teams)
    public function form(): MorphTo
    {
        return $this->morphTo();
    }

    public function defaultSurveyRows(): HasManyThrough
    {
        return $this->hasManyThrough(SurveyRow::class, XlsformModuleVersion::class)->where('xlsform_module_versions.is_default', true);
    }

    public function defaultChoiceLists(): HasManyThrough
    {
        return $this->hasManyThrough(ChoiceList::class, XlsformModuleVersion::class)->where('xlsform_module_versions.is_default', true);
    }

    public function defaultLocales(): HasManyDeep
    {
        return $this->hasManyDeep(
            Locale::class,
            [XlsformModuleVersion::class, XlsformModuleVersionLocale::class]
        )
            ->where('xlsform_module_versions.is_default', true);
    }

}
