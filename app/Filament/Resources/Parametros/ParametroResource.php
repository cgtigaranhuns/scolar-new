<?php

namespace App\Filament\Resources\Parametros;

use App\Filament\Resources\Parametros\Pages\ManageParametros;
use App\Models\Parametro;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class ParametroResource extends Resource
{
    protected static ?string $model = Parametro::class;

    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog';

    protected static ?string $label = 'Parâmetros';

    protected static ?string $navigationLabel = 'Parâmetros';

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';




    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Configurações do Sistema')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('campus')
                            ->label('Campus')
                            ->required(false),
                        TextInput::make('telefone')
                            ->label('Telefone')
                            ->mask('(99) 99999-9999')
                            ->required(false),
                        Textarea::make('endereco')
                            ->columnSpanFull()
                            ->autosize()
                            ->label('Endereço')
                            ->required(false),
                        TextInput::make('setor_ensino')
                            ->label('Setor de Ensino')
                            ->required(false),
                        TextInput::make('sigla_setor_ensino')
                            ->label('Sigla do Setor de Ensino')
                            ->required(false),
                    ]),
                Fieldset::make('Configurações de Versão')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('versao_sistema')
                            ->label('Versão do Sistema')
                            ->required(false),
                        TextInput::make('versao_banco_dados')
                            ->label('Versão do Banco de Dados')
                            ->required(false),
                        DatePicker::make('data_atualizacao_sistema')
                            ->label('Data de Atualização do Sistema')
                            ->required(false),
                        DatePicker::make('data_atualizacao_banco_dados')
                            ->label('Data de Atualização do Banco de Dados')
                            ->required(false),
                    ]),
                Fieldset::make('Configurações de Email')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('tipo_envio_email')
                            ->label('Tipo de Envio de Email')
                            ->required(false),
                        TextInput::make('servidor_email')
                            ->label('Servidor de Email')
                            ->required(false),
                        TextInput::make('porta_email')
                            ->label('Porta de Email')
                            ->required(false),
                        TextInput::make('email_seguro')
                            ->label('Email Seguro')
                            ->required(false),
                        TextInput::make('usuario_email')
                            ->prefix('email')
                            ->label('Usuário de Email')
                            ->required(false),
                        TextInput::make('senha_email')
                            ->label('Senha de Email')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->placeholder(
                                fn(string $context): string =>
                                $context === 'edit' ? '••••••••' : ''
                            ),
                        TextInput::make('email_copia')
                            ->prefix('email')
                            ->label('Email de Cópia')
                            ->required(false),
                        TextInput::make('email_administratador')
                            ->prefix('email')
                            ->label('Email de Administrador')
                            ->required(false),
                    ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campus')
                ->label('Campus')
                ->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageParametros::route('/'),
        ];
    }
}
