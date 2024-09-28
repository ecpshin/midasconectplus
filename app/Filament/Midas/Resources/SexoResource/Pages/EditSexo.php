<?php

namespace App\Filament\Midas\Resources\SexoResource\Pages;

use App\Filament\Midas\Resources\SexoResource;
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
        return route('filament.midas.resources.sexos.index');
    }
}
