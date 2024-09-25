<?php

namespace App\Filament\Admin\Resources\AgendamentoResource\Pages;

use App\Filament\Admin\Resources\AgendamentoResource;
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
