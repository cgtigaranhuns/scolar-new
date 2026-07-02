<?php

namespace App\Filament\Resources\Discentes;

use App\Filament\Resources\Discentes\Pages\ManageDiscentes;
use App\Models\Discente;
use App\Models\Turma;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;


class DiscenteResource extends Resource
{
    protected static ?string $model = Discente::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = 'Estudantes';
    protected static ?string $navigationLabel = 'Estudantes';
    protected static string|\UnitEnum|null $navigationGroup = 'Cadastros';

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User */
        $authUser =  auth()->user();

        if ($authUser->hasRole('Pais')) {
            return parent::getEloquentQuery()->where('matricula', '=', auth()->user()->username);
        } else {
            return static::getModel()::query();
        }
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->required(),
                TextInput::make('email_discente')
                    ->label('Email do Discente')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->email()
                    ->required(),
                TextInput::make('email_responsavel')
                    ->label('Email do Responsável')                    
                    ->email(),
                DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->date()
                    ->required(),
                TextInput::make('matricula')
                    ->label('Matrícula')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->required(),
                Select::make('turma')
                    ->label('Turma')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->relationship('turmaRelacionada', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('status_qa')
                    ->label('Status - Q-Academico')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->required(),
                 TextInput::make('senha_responsavel')
                    ->label('Senha do Responsável')
                    ->hidden(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                FileUpload::make('foto')
                    ->disk('public')
                    ->directory('fotos')
                    // Gerar o nome do arquivo com o número de matrícula
                    ->getUploadedFileNameForStorageUsing(function (Get $get) {
                        $matricula = $get('matricula');
                        $extension = pathinfo($get('foto')->getClientOriginalName(), PATHINFO_EXTENSION);
                        return $matricula . '.' . $extension;
                    })
                    ->visibility('public')
                    ->label('Foto'),                
               
                Textarea::make('informacoes_adicionais')
                    ->disabled(function () {
                        $authUser = auth()->user();
                        if ($authUser->hasRole('Pais')) {
                            return true;
                        }
                        return false;
                    })
                    ->label('Informações Adicionais')
                    ->autosize(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nome')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('foto')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('matricula')
                    ->searchable(),
                TextColumn::make('turmaRelacionada.nome')
                    ->searchable(),
                TextColumn::make('status_qa')
                    ->label('Status Q-Academico')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('turma')
                    ->label('Turma')
                    ->relationship('turmaRelacionada', 'nome')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('informacoes_adicionais')
                    ->label('Possui Informações Adicionais')
                    ->placeholder('Todos os estudantes')
                    ->nullable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->tooltip('Editar'),
                DeleteAction::make()
                    ->label('')
                    ->tooltip('Excluir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                  //  DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDiscentes::route('/'),
        ];
    }
}
