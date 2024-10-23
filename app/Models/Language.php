<?php

namespace App\Models;


use App\Models\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }

    public function columnHeaderSuffix(): Attribute
    {
        return new Attribute(
            get: fn() => $this->label . ' (' . $this->code . ')',
        );
    }
}
