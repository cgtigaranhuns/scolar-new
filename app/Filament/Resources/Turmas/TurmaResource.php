<?php

namespace App\Filament\Resources\Turmas;

use App\Filament\Resources\Turmas\Pages\ManageTurmas;
use App\Models\Turma;
use BackedEnum;
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
use Filament\Tables\Table;

class TurmaResource extends Resource
{
    protected static ?string $model = Turma::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-view-columns';
    protected static ?string $label = 'Turmas';
    protected static ?string $navigationLabel = 'Turmas';
    protected static string|\UnitEnum|null $navigationGroup = 'Cadastros';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('codigo')
                    ->label('Código')
                    ->required(false),
                Select::make('professores') // Nome da relação no plural
                    ->relationship('professores', 'nome') // Referência à nova relação belongsToMany
                    ->multiple()
                    ->preload()
                    ->label('Professores da Turma')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable(),
                TextColumn::make('codigo')
                    ->label('Código')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => ManageTurmas::route('/'),
        ];
    }
}
