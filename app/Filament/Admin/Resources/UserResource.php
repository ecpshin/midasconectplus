<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $navigationIcon = 'icon-users-cog-solid';

    protected static ?string $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')->label('Nome')->required(),
                    Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->unique(ignoreRecord: true)->required(),
                ]),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('password')
                        ->label('Senha')
                        ->password()
                        ->minLength(8)
                        ->maxLength(16)
                        ->revealable()
                        ->dehydrateStateUsing(fn(string $state): string => Hash::make($state)  )
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create'),
                    Forms\Components\TextInput::make('password_confirm')
                        ->label('Confirme a Senha')
                        ->password()
                        ->same('password')
                        ->minLength(8)
                        ->maxLength(16)
                        ->revealable()
                        ->dehydrated(false)
                        ->required(fn(string $operation) => $operation === 'create'),
                ])->visibleOn('create'),
                Forms\Components\Section::make([

                    Forms\Components\Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->label('Funções')
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')
                ->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('roles.name')->label('Função'),
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
            'index' => \App\Filament\Admin\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\Admin\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Filament\Admin\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
