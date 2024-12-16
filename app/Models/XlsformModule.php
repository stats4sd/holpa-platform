<?php

namespace App\Models;

use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class XlsformModule extends Model
{
    protected $table = 'xlsform_modules';

    public function xlsformTemplateModules(): HasMany
    {
        return $this->hasMany(XlsformModuleVersion::class);
    }

    // In a generalised version, this might be a BelongsToMany/MorphToMany, so we can use the same module (e.g. "diet diversity") in different templates.
    // That will come when we make it so that users can start to make their own templates.

    // Links to *either* XlsformTemplate (for standard / global modules) or Xlsform (for custom modules uploaded by teams)
    public function form(): MorphTo
    {
        return $this->morphTo();
    }

}
