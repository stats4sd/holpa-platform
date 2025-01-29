<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Continent extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function countries(): HasManyThrough
    {
        return $this->hasManyThrough(Country::class, Region::class);
    }

}
