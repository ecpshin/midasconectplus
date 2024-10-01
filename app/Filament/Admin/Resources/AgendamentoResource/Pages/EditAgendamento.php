<?php

namespace App\Filament\Admin\Resources\AgendamentoResource\Pages;

use App\Filament\Admin\Resources\AgendamentoResource;
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
        return route('filament.admin.resources.agendamentos.index');
    }
}
