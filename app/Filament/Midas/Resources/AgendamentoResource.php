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
                Forms\Components\Select::make('user_id')
                    ->relationship(name: 'user', titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query)=>$query->whereId(auth()->user()->id))
                        ->selectablePlaceholder(false),
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'id')
                    ->default(1),
                Forms\Components\Select::make('organizacao_id')
                    ->relationship('organizacao', 'id')
                    ->default(1),
                Forms\Components\Select::make('produto_id')
                    ->relationship('produto', 'id')
                    ->default(1),
                Forms\Components\DatePicker::make('data_ligacao'),
                Forms\Components\DatePicker::make('data_agendamento'),
                Forms\Components\TextInput::make('nome')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpf')
                    ->maxLength(255),
                Forms\Components\TextInput::make('matricula')
                    ->maxLength(255)
                    ->default(000),
                Forms\Components\TextInput::make('margem')
                    ->numeric()
                    ->default(0.000000),
                Forms\Components\TextInput::make('telefone')
                    ->tel()
                    ->maxLength(255)
                    ->default('(84)9 0000-0000'),
                Forms\Components\TextInput::make('orgao')
                    ->maxLength(255)
                    ->default('Não informado'),
                Forms\Components\TextInput::make('produto')
                    ->maxLength(255)
                    ->default('Não informado'),
                    Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),
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
