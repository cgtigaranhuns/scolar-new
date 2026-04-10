<?php

namespace App\Filament\Resources\Discentes\Pages;

use App\Filament\Resources\Discentes\DiscenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDiscentes extends ManageRecords
{
    protected static string $resource = DiscenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar Estudante')
                ->icon('heroicon-o-plus')
                ->modalHeading('Adicionar Estudante'),
        ];
    }
}
