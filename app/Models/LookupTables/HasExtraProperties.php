<?php

namespace App\Models\LookupTables;

use Illuminate\Support\Collection;

interface HasExtraProperties
{
    public static function getChoiceListName(): string;
    public static function extraProperties(): Collection;
}
