<?php

namespace App\Filament\Midas\Resources;

use App\Filament\Midas\Resources\SexoResource\Pages;
use App\Filament\Midas\Resources\SexoResource\RelationManagers;
use App\Models\Sexo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SexoResource extends Resource
{
    protected static ?string $model = Sexo::class;

    protected static ?string $navigationGroup = 'Dependências';

    protected static ?string $navigationIcon = 'icon-venus-mars-solid';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('sexo')
                        ->maxLength(255),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sexo')
                    ->searchable(),
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
            'index' => Pages\ListSexos::route('/'),
        ];
    }
}
