<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

trait HasTableComissaoTab
{
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Digitados')->modifyQueryUsing(fn(Builder $query) => $query->whereSituacaoId(1)),
            'aprovados' => Tab::make('Aprovados')->modifyQueryUsing(fn(Builder $query) => $query->whereSituacaoId(2)),
            'cancelados' => Tab::make('Cancelados')->modifyQueryUsing(fn(Builder $query) => $query->whereSituacaoId(3))
        ];
    }
}
