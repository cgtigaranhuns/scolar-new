<?php

namespace App\Filament\Resources\Conselhos;

use App\Filament\Resources\Conselhos\Pages\CreateConselho;
use App\Filament\Resources\Conselhos\Pages\EditConselho;
use App\Filament\Resources\Conselhos\Pages\ListConselhos;
use App\Filament\Resources\Conselhos\RelationManagers\DiscentesConselhoRelationManager;
use App\Filament\Resources\Conselhos\Schemas\ConselhoForm;
use App\Filament\Resources\Conselhos\Tables\ConselhosTable;
use App\Models\Conselho;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConselhoResource extends Resource
{
    protected static ?string $model = Conselho::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Conselhos de Classe';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $title = 'Cadastro de Conselho';

    public static function form(Schema $schema): Schema
    {
        return ConselhoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConselhosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DiscentesConselhoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConselhos::route('/'),
            'create' => CreateConselho::route('/create'),
            'edit' => EditConselho::route('/{record}/edit'),
        ];
    }
}
