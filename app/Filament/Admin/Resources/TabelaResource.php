<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\TabelaResource\Pages;
use App\Filament\Resources\TabelaResource\RelationManagers;
use App\Models\Tabela;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TabelaResource extends Resource
{
    protected static ?string $model = Tabela::class;

    protected static ?string $navigationGroup = 'Principal';

    protected static ?string $navigationIcon = 'icon-receipt';
    protected static ?string $navigationParentItem = 'Comissões';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Fieldset::make()->schema([

                        Forms\Components\Group::make([

                            Forms\Components\Select::make('produto_id')->label('Produto')
                                ->relationship('produto', 'descricao_produto')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('descricao_produto')
                                        ->label("Descrição do Produto")
                                        ->required()
                                        ->maxLength(255)
                                ]),

                            Forms\Components\Select::make('financeira_id')->label("Financeira")
                                ->relationship('financeira', 'nome_financeira')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('nome_financeira')->label('Nome Financeira')
                                ]),

                            Forms\Components\Select::make('correspondente_id')->label('Correspondente')
                                ->relationship('correspondente', 'nome_correspondente')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\Section::make([
                                        Forms\Components\TextInput::make('nome_correspondente')->label('Nome Correspondente')
                                    ])
                                ]),

                            Forms\Components\Select::make('organizacao_id')->label('Organização|Órgão')
                                ->relationship('organizacao', 'nome_organizacao')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\Section::make([
                                        Forms\Components\TextInput::make('nome_organizacao')->label('Nome Organização'),
                                    ])
                                ]),

                        ])->columns(['lg' => 4])->columnSpan(['lg' => 'full']),

                    ]),

                    Forms\Components\Fieldset::make()->schema([

                        Forms\Components\Group::make([

                            Forms\Components\TextInput::make('descricao')
                                ->maxLength(100)
                                ->default(null),

                            Forms\Components\TextInput::make('codigo')
                                ->maxLength(100)
                                ->default(null),

                        ])->columns(['lg' => 2])->columnSpan(['lg' => 'full']),
                    ]),

                    Forms\Components\Fieldset::make()->schema([

                        Forms\Components\Group::make([


                            Forms\Components\Group::make([

                                Forms\Components\TextInput::make('percentual_diferido')
                                    ->label('Diferido %')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0.00)
                                    ->maxValue(100.00)
                                    ->suffix('%')
                                    ->default('0,00'),

                                Forms\Components\TextInput::make('prazo')
                                    ->maxLength(50)
                                    ->default(null),

                                Forms\Components\Select::make('referencia')
                                    ->label('Referência')
                                    ->options([
                                        'B' => 'Bruto',
                                        'BL' => 'Bruto|Liquido',
                                        'L' => 'Líquido',
                                    ])->default('L'),

                                Forms\Components\Toggle::make('parcelado')
                                    ->label('Comissão Parcelada')
                                    ->inlineLabel()
                                    ->default(false),
                            ]),

                            Forms\Components\Group::make([
                                Forms\Components\TextInput::make('percentual_loja')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0.00)
                                    ->maxValue(100.00)
                                    ->suffix('%')
                                    ->default('0,00'),

                                Forms\Components\TextInput::make('percentual_agente')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0.00)
                                    ->maxValue(100.00)
                                    ->suffix('%')
                                    ->default('0,00'),

                                Forms\Components\TextInput::make('percentual_corretor')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0.00)
                                    ->maxValue(100.00)
                                    ->suffix('%')
                                    ->default('0,00'),

                            ]),

                            Forms\Components\Group::make([

                                Forms\Components\TextInput::make('bonus')->label('Bônus')
                                    ->numeric()->step(0.01)
                                    ->minValue(0.00)
                                    ->maxValue(100.00)
                                    ->suffix('%')
                                    ->default('0,00'),

                            ])

                        ])->columns(['lg' => 4])->columnSpan(['lg' => 'full']),

                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto.descricao_produto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prazo'),
                Tables\Columns\TextColumn::make('financeira.nome_financeira')
                    ->sortable(),
                Tables\Columns\TextColumn::make('correspondente.nome_correspondente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizacao.nome_organizacao')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('parcelado')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('percentual_loja')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bonus')
                    ->sortable(),
                Tables\Columns\TextColumn::make('percentual_diferido')
                    ->sortable(),
                Tables\Columns\TextColumn::make('percentual_agente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('percentual_corretor')
                    ->sortable(),
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
            'index' => \App\Filament\Admin\Resources\TabelaResource\Pages\ListTabelas::route('/'),
            'create' => \App\Filament\Admin\Resources\TabelaResource\Pages\CreateTabela::route('/create'),
            'edit' => \App\Filament\Admin\Resources\TabelaResource\Pages\EditTabela::route('/{record}/edit'),
        ];
    }
}
