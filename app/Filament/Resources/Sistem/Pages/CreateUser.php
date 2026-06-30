<?php

namespace App\Filament\Resources\Sistem\Pages;

use App\Filament\Resources\Sistem\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
