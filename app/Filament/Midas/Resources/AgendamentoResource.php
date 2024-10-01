<?php

namespace App\Filament\Midas\Resources;

use App\Filament\Midas\Resources\AgendamentoResource\Pages;
use App\Filament\Midas\Resources\AgendamentoResource\RelationManagers;
use App\Models\Agendamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgendamentoResource extends Resource
{
    protected static ?string $model = Agendamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Call Center';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name',
                            modifyQueryUsing: fn(Builder $query): Builder => $query->whereId(auth()->user()->id))
                        ->selectablePlaceholder(false)
                        ->default(auth()->user()->id),
                    Forms\Components\Select::make('status_id')->label('Status')
                        ->relationship('status', 'status')
                        ->default(1),
                    Forms\Components\Select::make('organizacao_id')->label('Órgão')
                        ->relationship('organizacao', 'nome_organizacao')                    ,
                    Forms\Components\Select::make('produto_id')->label('Produto oferecido')
                        ->relationship('produto', 'descricao_produto'),
                ])->columns(['xl' => 4]),
                Forms\Components\Section::make([
                    Forms\Components\DatePicker::make('data_ligacao')
                        ->label('Data Contato'),
                    Forms\Components\DatePicker::make('data_agendamento')
                        ->label('Data agendada'),
                ])->columns(['xl' => 2]),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('nome')
                        ->maxLength(255)
                        ->columnSpan(['xl' => 3])
                        ->default(null),
                    Forms\Components\TextInput::make('cpf')
                        ->mask('999.999.999-99')
                        ->maxLength(14)
                        ->columnSpan(['xl' => 1])
                        ->default(null),
                ])->columns(['xl' => 4]),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('matricula')
                        ->maxLength(50)
                        ->default('000000'),
                    Forms\Components\TextInput::make('margem')
                        ->numeric()
                        ->minValue(0.00)
                        ->maxValue(1000000.00)
                        ->step(0.00)
                        ->default('0.00'),
                    Forms\Components\TextInput::make('telefone')
                        ->tel()
                        ->mask('(99)9 9999-9999')
                        ->maxLength(50)
                        ->default('(84)9 0000-0000'),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('orgao')
                            ->maxLength(255)
                            ->readOnly(true)
                            ->default('N達o informado'),
                        Forms\Components\TextInput::make('produto')
                            ->label('Produto sugestionado')
                            ->readOnly(),
                    ])->columns(['xl'=>2])->columnSpan(['xl' => 'full']),
                    Forms\Components\Textarea::make('observacoes')
                        ->columnSpanFull(),
                ])->columns(['xl' => 3]),
            ]);
    }

            public static function table(Table $table): Table
    {
        return $table
        ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_ligacao')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_agendamento')
                    ->date('d/m/Y')
                    ->sortable()
            ])->modifyQueryUsing(fn(Builder $query) => $query->whereUserId(auth()->user()->id)->whereNotNull('data_agendamento'))
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
            'index' => Pages\ListAgendamentos::route('/'),
            'create' => Pages\CreateAgendamento::route('/create'),
            'edit' => Pages\EditAgendamento::route('/{record}/edit'),
        ];
    }
}
