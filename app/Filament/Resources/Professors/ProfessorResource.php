<?php

namespace App\Filament\Resources\Professors;

use App\Filament\Resources\Professors\Pages\ManageProfessors;
use App\Models\Professor;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'Professores';
    protected static ?string $navigationLabel = 'Professores';
    protected static string|\UnitEnum|null $navigationGroup = 'Cadastros';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('matricula')
                    ->required(),
                Select::make('area_conhecimento_id')
                    ->label('Área de Conhecimento')
                    ->relationship('areaConhecimento', 'nome')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable(),
                TextColumn::make('matricula')
                    ->label('Matrícula')
                    ->searchable(),
                TextColumn::make('turmas.nome')                   
                    ->label('Turmas')
                    ->listWithLineBreaks() // Opcional: melhora a visualização de múltiplos nomes
                    ->bulleted()           // Opcional: formata como lista
                    ->searchable(),
                TextColumn::make('areaConhecimento.nome')
                    ->label('Área de Conhecimento')
                    ->sortable()
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
                SelectFilter::make('area_conhecimento_id')
                    ->label('Área de Conhecimento')
                    ->relationship('areaConhecimento', 'nome')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('turmas.id')
                    ->label('Turma')
                    ->relationship('turmas', 'nome')
                    ->searchable()
                    ->preload(),
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
                   // DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProfessors::route('/'),
        ];
    }
}
