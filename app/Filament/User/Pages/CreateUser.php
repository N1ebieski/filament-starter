<?php

namespace App\Filament\Resources\Admin\User\Pages;

use Filament\Actions;
use App\Filament\Resources\Admin\User\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
