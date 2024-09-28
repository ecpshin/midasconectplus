<?php

namespace App\Filament\Admin\Resources\SexoResource\Pages;

use App\Filament\Admin\Resources\SexoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSexo extends EditRecord
{
    protected static string $resource = SexoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string{
        return route('filament.admin.resources.sexos.index');
    }
}
