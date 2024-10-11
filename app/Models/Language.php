<?php

namespace App\Models;

use App\Models\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }
}