<?php

namespace App\Filament\Resources\Conselhos\Pages;

use App\Filament\Resources\Conselhos\ConselhoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListConselhos extends ListRecords
{
    protected static string $resource = ConselhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar Conselho')
                ->icon('heroicon-o-plus')
                ->modalHeading('Adicionar Conselho')

        ];
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->recordUrl(
                fn($record): string => ConselhoResource::getUrl('edit', ['record' => $record])
            );
    }
}
