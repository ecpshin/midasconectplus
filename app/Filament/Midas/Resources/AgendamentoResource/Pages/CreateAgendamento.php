<?php

namespace App\Filament\Midas\Resources\AgendamentoResource\Pages;

use App\Filament\Midas\Resources\AgendamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgendamento extends CreateRecord
{
    protected static string $resource = AgendamentoResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.midas.resources.agendamentos.index');
    }
}
