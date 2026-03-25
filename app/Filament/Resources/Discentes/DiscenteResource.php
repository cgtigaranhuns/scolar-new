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
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Hash;

class DiscenteResource extends Resource
{
    protected static ?string $model = Discente::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = 'Estudantes';
    protected static ?string $navigationLabel = 'Estudantes';
    protected static string|\UnitEnum|null $navigationGroup = 'Cadastros';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('email_discente')
                    ->label('Email do Discente')
                    ->email()
                    ->required(),
                TextInput::make('email_responsavel')
                    ->label('Email do Responsável')
                    ->email(),
                DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->date()
                    ->required(),
                TextInput::make('matricula')
                    ->label('Matrícula')
                    ->required(),
                Select::make('turma')
                    ->label('Turma')
                    ->relationship('turmaRelacionada', 'nome')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('status_qa')
                    ->label('Status - Q-Academico')
                    ->required(),
                FileUpload::make('foto')
                    ->disk('public')
                    ->directory('fotos')
                    ->visibility('public')
                    ->label('Foto'),
                TextInput::make('informacoes_adicionais'),
                 TextInput::make('senha_responsavel')
                    ->label('Senha do Responsável')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('matricula')
                    ->searchable(),
                TextColumn::make('turmaRelacionada.nome')
                    ->searchable(),
                TextColumn::make('status_qa')
                    ->label('Status - Q-Academico')
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
            'index' => ManageDiscentes::route('/'),
        ];
    }
}
