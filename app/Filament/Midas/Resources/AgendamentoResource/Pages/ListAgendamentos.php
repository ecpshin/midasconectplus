<?php

namespace App\Filament\Midas\Resources\AgendamentoResource\Pages;

use App\Filament\Midas\Resources\AgendamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgendamentos extends ListRecords
{
    protected static string $resource = AgendamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
