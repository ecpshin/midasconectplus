<?php

namespace App\Filament\Admin\Resources;

use App\Models\Tabela;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Tables;
use App\Models\Proposta;
use Filament\Forms\Form;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\PropostaResource\Pages;
use App\Filament\Admin\Resources\PropostaResource\RelationManagers;

class PropostaResource extends Resource
{
    protected static ?string $model = Proposta::class;

    protected static ?string $navigationGroup = "Principal";
    protected static ?int $navigationSort = -1;

    protected static ?string $navigationIcon = 'icon-briefcase-fill';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Fieldset::make()->schema([
                        Forms\Components\Select::make('user_id')->label('Agente|Corretor')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('cliente_id')
                                ->relationship('cliente', 'nome', fn(Builder $query) => $query->orderBy('nome')->orderBy('cpf'))
                                ->searchable(['nome', 'cpf'])
                                ->preload()
                                ->required(),
                        ])->columnSpanFull(),
                    ])->columns(3),
                    Forms\Components\Fieldset::make()->schema([

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('uuid')
                                ->label('UUID')
                                ->maxLength(50)
                                ->default(substr(\Ramsey\Uuid\Uuid::uuid4(), 0, 13)),
                            Forms\Components\TextInput::make('numero_contrato')
                                ->maxLength(50)
                                ->default('Não informado'),
                            Forms\Components\DatePicker::make('data_digitacao')
                                ->label('Data digitação'),
                            Forms\Components\DatePicker::make('data_pagamento')
                                ->label('Data finalização'),
                        ])->columns(['lg' => 4]),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('prazo_proposta')
                                ->numeric()
                                ->default('0'),
                            Forms\Components\TextInput::make('total_proposta')
                                ->prefix('R$')
                                ->numeric()
                                ->live()
                                ->step(0.01)
                                ->default('0.00'),
                            Forms\Components\TextInput::make('parcela_proposta')
                                ->prefix('R$')
                                ->live()
                                ->numeric()
                                ->step(0.01)
                                ->default('0.00'),
                            Forms\Components\TextInput::make('liquido_proposta')
                                ->prefix('R$')
                                ->live()
                                ->numeric()
                                ->step(0.01)
                                ->default('0.00'),

                            Forms\Components\Select::make('situacao_id')->label('Situação')
                                ->relationship('situacao', 'descricao_situacao')
                                ->required(),
                        ])->columns(['lg' => 5]),
                    ])
                        ->columns(1),
                    Forms\Components\Fieldset::make()->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('organizacao_id')->label('Orgão')
                                ->relationship('organizacao', 'nome_organizacao')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('produto_id')->label('Produto|Operação')
                                ->relationship('produto', 'descricao_produto')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('financeira_id')->label('Financeira')
                                ->relationship('financeira', 'nome_financeira')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('correspondente_id')->label('Correspondente')
                                ->relationship('correspondente', 'nome_correspondente')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(['lg' => 4])->columnSpanFull(),
                    ])->columns(1),
                ]),
                Forms\Components\Section::make([
                    Forms\Components\Fieldset::make()->schema([
                        Forms\Components\Select::make('tabela_id')->label('Tabela')
                            ->relationship('tabela', 'descricao_codigo')
                            ->live()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set): void {
                                $tabela = Tabela::whereId($state)->get(['percentual_agente', 'percentual_corretor', 'percentual_loja', 'referencia', 'parcelado'])->toArray()[0];
                                $calculo = 0.0;
                                $liquido = $get('liquido_proposta');
                                $total = ($get('total_proposta'));

                                $p_agente = $tabela['percentual_agente'];
                                $p_corretor = $tabela['percentual_corretor'];
                                $p_loja = $tabela['percentual_loja'];

                                $set('percentual_agente', $p_agente);
                                $set('percentual_corretor', $p_corretor);
                                $set('percentual_loja', $p_loja);

                                if ($tabela['referencia'] === 'L') {
                                    $set('valor_agente', calcularValor($liquido, $p_agente));
                                    $set('valor_corretor', calcularValor($liquido, $p_corretor));
                                    $set('valor_loja', calcularValor($liquido, $p_loja));
                                }
                            })
                            ->columnSpanFull(),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('percentual_loja')->label('% Loja')
                                ->numeric()
                                ->step(0.01)
                                ->maxValue(100.00)
                                ->prefix("%")
                                ->default('0.00'),
                            Forms\Components\TextInput::make('valor_loja')->label('R$ Loja ')
                                ->numeric()
                                ->step(0.01)
                                ->maxValue(10000000.00)
                                ->prefix('R$')
                                ->default('0.00')
                        ])->visible(auth()->user()->hasRole(Utils::getSuperAdminName())),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('percentual_agente')
                                ->label('% Agente')
                                ->numeric()
                                ->step(0.01)
                                ->maxValue(100.00)
                                ->prefix("%")
                                ->default('0.00'),
                            Forms\Components\TextInput::make('valor_agente')
                                ->label('R$ Agente')
                                ->numeric()
                                ->step(0.01)
                                ->maxValue(10000000.00)
                                ->prefix('R$')
                                ->default(0.00)
                        ]),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('percentual_corretor')
                                ->label('% Corretor')
                                ->numeric()
                                ->step(0.01)
                                ->maxValue(100.00)
                                ->prefix("%")
                                ->default('0.00'),
                            Forms\Components\TextInput::make('valor_corretor')
                                ->label('R$ Corretor')
                                ->numeric()
                                ->live()
                                ->step(0.01)
                                ->maxValue(10000000.00)
                                ->prefix('R$')
                                ->default('0.00'),
                        ]),

                    ])->columns(['lg' => 3])
                ])//section 2
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.cpf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_contrato')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_digitacao')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_pagamento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prazo_proposta')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_proposta')
                    ->numeric(decimalPlaces: 2)
                    ->money('BRL')
                    ->summarize(summarizers: [
                        Sum::make('total_proposta')->label('Total Bruto'),
                        Average::make('liquido_proposta')->label('Média líquido')])
                    ->sortable(),
                Tables\Columns\TextColumn::make('parcela_proposta')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('liquido_proposta')
                    ->money('BRL')
                    ->summarize(summarizers: [
                        Sum::make('liqudo_proposta')->label('Total Líquido'),
                        Average::make('liquido_proposta')->label('Média líquido')
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('produto.descricao_produto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('financeira.nome_financeira')
                    ->sortable(),
                Tables\Columns\TextColumn::make('correspondente.nome_correspondente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('situacao.descricao_situacao')
                    ->sortable()
            ])
            ->filters([
                //
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropostas::route('/'),
            'create' => Pages\CreateProposta::route('/create'),
            'edit' => Pages\EditProposta::route('/{record}/edit'),
        ];
    }

}

function calcularValor($valor, $percentual): float
{
    return round(doubleval($valor) * doubleval($percentual) / 100, 2);
}
