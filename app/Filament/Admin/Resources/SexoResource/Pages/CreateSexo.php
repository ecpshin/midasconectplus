<?php

namespace App\Filament\Admin\Resources\SexoResource\Pages;

use App\Filament\Admin\Resources\SexoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSexo extends CreateRecord
{
    protected static string $resource = SexoResource::class;

    protected function getRedirectUrl(): string{
        return route('filament.admin.resources.sexos.index');
    }
}
