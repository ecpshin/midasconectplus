<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TipoContaEnum;
use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use App\Models\EstadoCivil;
use App\Models\Organizacao;
use App\Services\BuscasApiService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationGroup = 'Principal';

    protected static ?string $navigationIcon = 'heroicon-s-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('cpf')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\DatePicker::make('data_nascimento'),
                    ])->columns(['xl' => 2]),

                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('rg')
                            ->maxLength(50)
                            ->default('Não informado'),
                        Forms\Components\TextInput::make('orgao_exp')
                            ->maxLength(50)
                            ->default('Não informado'),
                        Forms\Components\DatePicker::make('data_exp'),
                        Forms\Components\TextInput::make('naturalidade')
                            ->maxLength(100)
                            ->default('Não informado'),
                    ])->columnSpan(['xl' => 'full'])->columns(['xl' => 4]),

                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('genitora')
                            ->maxLength(100)
                            ->default('Não informado'),
                        Forms\Components\TextInput::make('genitor')
                            ->maxLength(100)
                            ->default('Não informado'),
                        Forms\Components\TextInput::make('sexo')
                            ->maxLength(50)
                            ->default('Masculino'),
                        Forms\Components\Select::make('estado_civil')
                            ->options(EstadoCivil::all()->pluck('estado_civil', 'estado_civil')->toArray())
                            ->createOptionForm([
                                Forms\Components\TextInput::make('estado_civil')
                            ])->createOptionUsing(function (array $data): string {
                                $estado = EstadoCivil::create($data);
                                return $estado->get('estado_civil');
                            }),
                    ])->columnSpan(['xl' => 'full'])->columns(['xl' => 4]),

                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('phone1')
                            ->tel()
                            ->maxLength(50)
                            ->mask('(84)9 9999-9999')
                            ->default('99999999999'),
                        Forms\Components\TextInput::make('phone2')
                            ->tel()
                            ->mask('(84)9 9999-9999')
                            ->maxLength(50)
                            ->default('99999999999'),
                        Forms\Components\TextInput::make('phone3')
                            ->tel()
                            ->mask('(84)9 9999-9999')
                            ->maxLength(50)
                            ->default('99999999999'),
                        Forms\Components\TextInput::make('phone4')
                            ->tel()
                            ->mask('(84)9 9999-9999')
                            ->maxLength(50)
                            ->default('99999999999'),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(),
                        Forms\Components\Textarea::make('observacoes')
                            ->label('Observações')->default(null)
                            ->columnSpanFull(),
                    ])->columnSpan(['xl' => 'full'])->columns(['xl' => 5]),

                ])->columns(['xl' => 2]),

                Forms\Components\Section::make('Dados Residenciais')->schema([
                    Forms\Components\Repeater::make('residenciais')->schema([
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('cep')
                                ->mask('99999-999')
                                ->required()
                                ->maxLength(9)
                                ->suffixAction(fn($state, $set) => Forms\Components\Actions\Action::make('search-action')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->action(function () use ($state, $set) {
                                        if (blank($state)) {
                                            Notification::make()
                                                ->title('Digite um CEP para buscar o endereço')
                                                ->danger()
                                                ->send();
                                            return;
                                        }

                                        $dataCep = BuscasApiService::buscaCep($state);

                                        $set('logradouro', $dataCep['street']);
                                        $set('bairro', $dataCep['neighborhood']);
                                        $set('localidade', $dataCep['city']);
                                        $set('uf', $dataCep['state']);
                                    })),
                            Forms\Components\TextInput::make('logradouro')
                                ->maxLength(255)
                                ->columnSpan(['lg' => 2]),
                            Forms\Components\TextInput::make('complemento')
                                ->maxLength(100)
                                ->columnSpan(['lg' => 2]),
                        ])->columnSpanFull()->columns(['lg' => 5]),
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('bairro')
                                ->maxLength(100)
                                ->columnSpan(['lg' => 2]),
                            Forms\Components\TextInput::make('localidade')
                                ->maxLength(100)
                                ->columnSpan(['lg' => 2]),
                            Forms\Components\TextInput::make('uf')
                                ->maxLength(2)
                        ])->columnSpanFull()->columns(['lg' => 5])
                    ]),
                ])->collapsible()->collapsible(),

                Forms\Components\Section::make('Dados bancários')->schema([
                    Forms\Components\Repeater::make('bancarias')->schema([
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('codigo')
                                ->required()
                                ->maxLength(50)
                                ->suffixAction(
                                    fn($state, $set) => Forms\Components\Actions\Action::make('search-action')
                                        ->icon('heroicon-o-magnifying-glass')
                                        ->action(
                                            function () use ($state, $set) {
                                                if(blank($state)){
                                                    Notification::make()
                                                        ->title('Digite o código para buscar o banco')
                                                        ->danger()
                                                        ->send();
                                                    return;
                                                }

                                                $dataBank = BuscasApiService::buscaBanco($state);

                                                if(key_exists('message', $dataBank))
                                                {
                                                    Notification::make()->title($dataBank['message'])
                                                        ->danger()
                                                        ->send();
                                                } else {
                                                    $set('banco', strtoupper($dataBank['fullName']));
                                                }
                                            }
                                        )
                                ),
                            Forms\Components\TextInput::make('banco')
                                ->columnSpan(['lg' => 2])
                                ->maxLength(255),
                            Forms\Components\TextInput::make('agencia'),
                            Forms\Components\TextInput::make('conta')
                        ])->columns(['lg' => 5]),
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('tipo')
                                ->options(TipoContaEnum::class)
                                ->default('Conta Corrente'),
                            Forms\Components\Select::make('operacao')
                                ->options([
                                    'Crédito em Conta' => 'Credito em Conta',
                                    'Débito em Conta' => 'Débito em Conta',
                                    'Ordem de Pagamento' => 'Ordem de Pagamento',
                                    'PIX' => 'PIX',
                                    'TED' => 'TED',
                                ]),
                            Forms\Components\TextInput::make('chave_pix')
                                ->label('Chave PIX'),
                        ])->columns(['lg' => 3]),
                ])->collapsible(),

            ])->collapsible()->collapsed(),

                Forms\Components\Section::make('Dados Funcionais')->schema([
                    Forms\Components\Repeater::make('funcionais')->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('organizacao_id')
                                ->options(Organizacao::all()->pluck('nome_organizacao', 'nome_organizacao'))
                                ->searchable()
                                ->preload()
                                ->columnSpan(['lg' => 3]),
                            Forms\Components\TextInput::make('nrbeneficio')
                                ->label('Benefício|Matrícula')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->maxLength(255)
                                ->default('cliente@email.com'),
                            Forms\Components\TextInput::make('senha')
                                ->label('Senha de acesso')
                                ->default('Não possui.'),
                        ])->columns(['lg' => 3]),
                    ])
                ])->collapsible()->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('nome')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orgao_exp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_exp')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Agente')
                    ->relationship('user', 'name')
            ], Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('id'));
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\ClienteResource\RelationManagers\PropostaRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\ClienteResource\Pages\ListClientes::route('/'),
            'create' => \App\Filament\Admin\Resources\ClienteResource\Pages\CreateCliente::route('/create'),
            'edit' => \App\Filament\Admin\Resources\ClienteResource\Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
