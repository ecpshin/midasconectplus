<?php

namespace App\Filament\Midas\Resources\SexoResource\Pages;

use App\Filament\Midas\Resources\SexoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSexos extends ListRecords
{
    protected static string $resource = SexoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
