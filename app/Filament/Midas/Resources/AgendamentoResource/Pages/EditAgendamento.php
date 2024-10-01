<?php

namespace App\Filament\Midas\Resources\AgendamentoResource\Pages;

use App\Filament\Midas\Resources\AgendamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendamento extends EditRecord
{
    protected static string $resource = AgendamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return route('filament.midas.resources.agendamentos.index');
    }
}
