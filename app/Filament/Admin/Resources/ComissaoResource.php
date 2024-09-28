<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Tables;
use App\Models\Comissao;
use App\Models\Proposta;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use BezhanSalleh\FilamentShield\Support\Utils;
use App\Filament\Resources\ComissaoResource\Pages;
use App\Filament\Resources\ComissaoResource\RelationManagers;

class ComissaoResource extends Resource
{
    protected static ?string $model = Comissao::class;

    protected static ?string $modelLabel = 'Comissão';
    protected static ?string $pluralModelLabel = 'Comissões';
    protected static ?string $navigationLabel = 'Comissões';
    protected static ?string $slug = 'comissoes-agente';
    protected static ?string $navigationGroup = 'Principal';
    protected static ?string $navigationIcon = 'icon-percent-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([

                    Forms\Components\Section::make([

                        Forms\Components\TextInput::make('uuid')

                        ->label('Proposta UUID')
                            ->disabledOn('edit'),

                            Forms\Components\Select::make('tabela_id')
                                ->label('Tabela')
                                ->relationship('tabela', 'descricao')
                                ->disabledOn('edit'),

                            Forms\Components\Group::make([

                                Forms\Components\TextInput::make('total_proposta')->label('Total proposta'),

                                Forms\Components\TextInput::make('liquido_proposta')->label('Líquido proposta')

                            ])->columns(['lg' => 2])

                    ]),
                    Forms\Components\Section::make([

                        Forms\Components\Group::make([

                            Forms\Components\TextInput::make('percentual_loja')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0.00)
                                ->maxValue(100.00),

                                Forms\Components\TextInput::make('valor_loja')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0.00)
                                ->maxValue(1000000.00),

                                ])->columns(['lg' => 2]),

                        Forms\Components\Group::make([

                            Forms\Components\TextInput::make('percentual_agente')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0.00)
                                ->maxValue(100.00),
                            Forms\Components\TextInput::make('valor_agente')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0.00)
                                ->maxValue(1000000.00),

                                ])->columns(['lg' => 2]),

                        Forms\Components\Group::make([

                            Forms\Components\TextInput::make('percentual_corretor')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0.00)
                                ->maxValue(100.00),

                                Forms\Components\TextInput::make('valor_corretor')
                                ->numeric()
                                ->step(0.01)
                                ->minValue(0.00)
                                ->maxValue(1000000.00),

                                ])->columns(['lg' => 2]),
                    ])
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        $is_super = Utils::getSuperAdminName();
        $is_corretor = config('filament-shield.midas_user.enabled', false);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Agente|Corretor'),
                Tables\Columns\TextColumn::make('numero_contrato'),
                Tables\Columns\TextColumn::make('cliente.nome'),
                Tables\Columns\TextColumn::make('tabela.descricao'),

                Tables\Columns\TextColumn::make('total_proposta')
                    ->money('BRL')
                    ->summarize(Sum::make('total_proposta')->money('BRL')),

                Tables\Columns\TextColumn::make('liquido_proposta')
                    ->money('BRL')
                    ->summarize(Sum::make('liquido_proposta')->money('BRL')),

                Tables\Columns\TextColumn::make('valor_agente')
                    ->money('BRL')
                    ->summarize(summarizers: Sum::make('valor_agente')),
                    Tables\Columns\TextColumn::make('valor_loja')
                    ->money('BRL')
                    ->summarize(summarizers: Sum::make('valor_loja'))
                    ->visible(auth()->user()->hasRole(Utils::getSuperAdminName()))
            ])
            ->filters([
                Filter::make('data_digitacao')->form([
                    Forms\Components\Group::make([
                        DatePicker::make('inicio'),
                        DatePicker::make('fim')
                    ])->columns(2)
                ])->query(function(Builder $query, array $data){
                    return $query
                        ->when($data['inicio'],
                            fn(Builder $query, $date): Builder => $query->where('data_digitacao','>=',$date))
                        ->when($data['fim'],
                            fn(Builder $query, $date): Builder => $query->where('data_digitacao','<=',$date));
                }),
                SelectFilter::make('user_id')->relationship('user', 'name')
            ], layout: FiltersLayout::AboveContentCollapsible)->persistFiltersInSession(true)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\ComissaoResource\Pages\ListComissaos::route('/'),
            'create' => \App\Filament\Admin\Resources\ComissaoResource\Pages\CreateComissao::route('/create'),
            'edit' => \App\Filament\Admin\Resources\ComissaoResource\Pages\EditComissao::route('/{record}/edit'),
        ];
    }
}
