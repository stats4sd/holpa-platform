<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Xlsform extends \Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform
{
    // overwrite to use the app model;
    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
    }
}
