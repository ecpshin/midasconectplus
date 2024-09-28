<?php

namespace App\Filament\Admin\Resources\ClienteResource\RelationManagers;

use App\Models\Tabela;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use function App\Filament\Admin\Resources\calcularValor;

class PropostaRelationManager extends RelationManager
{
    protected static string $relationship = 'propostas';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nrcontrole')
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->label('UUID'),
                Tables\Columns\TextColumn::make('data_digitacao')->label('Data digitacao')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('total_proposta')->label('Total Proposta'),
                Tables\Columns\TextColumn::make('liquido_proposta')->label('Líquido Proposta'),
                Tables\Columns\TextColumn::make('parcela_proposta'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
