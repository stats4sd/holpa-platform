<?php

namespace App\Models\Interfaces;


// TODO: this might perhaps be merged with `WithXlsformDrafts`.
// Right now, this is used for modules because this doesn't require that the module can be deployed directly to ODK Central
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Znck\Eloquent\Relations\BelongsToThrough;

interface WithXlsformFile
{
    public function xlsfile(): Attribute;

    public function surveyRows(): MorphMany|HasManyDeep;

    public function choiceLists(): MorphMany;

    public function languageStrings(): HasManyThrough;

    public function choiceListEntries(): HasManyThrough;

    public function xlsformTemplateLanguages(): MorphMany;

}
