<?php

namespace App\Filament\Admin\Resources\PrefeituraResource\Pages;

use App\Filament\Admin\Resources\PrefeituraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrefeitura extends EditRecord
{
    protected static string $resource = PrefeituraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}