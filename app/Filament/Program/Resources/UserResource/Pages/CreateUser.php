<?php

namespace App\Filament\Program\Resources\UserResource\Pages;

use App\Filament\Program\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
