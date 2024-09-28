<?php

namespace App\Filament\Midas\Widgets;

use App\Models\Agendamento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsLigacoesOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $ligacoes = Agendamento::whereUserId(auth()->id());

        return [
            Stat::make(auth()->user()->name . ' você realizou: ', $ligacoes->count())
            ->icon('heroicon-s-phone-arrow-up-right')
            ->color('success')
            ->description('Ligações realizadas')
            ->descriptionColor('warning'),
            Stat::make('Clientes agendados', $ligacoes->whereNotNull('data_agendamento')->count()),
        ];
    }
}
