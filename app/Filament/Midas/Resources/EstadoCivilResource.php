<?php

namespace App\Filament\Midas\Resources;

use App\Filament\Midas\Resources\EstadoCivilResource\Pages;
use App\Filament\Midas\Resources\EstadoCivilResource\RelationManagers;
use App\Models\EstadoCivil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstadoCivilResource extends Resource
{
    protected static ?string $model = EstadoCivil::class;
    protected static ?string $modelLabel = 'Estado Civil';
    protected static ?string $pluralModelLabel = 'Estados Civil';

    protected static ?string $navigationIcon = 'icon-couple-marriage';

    protected static ?string $navigationGroup = 'Dependências';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cadastrar Estado Civil')->schema([
                    Forms\Components\TextInput::make('estado_civil')->label('Estado Civil')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estado_civil')
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEstadoCivils::route('/'),
        ];
    }
}
