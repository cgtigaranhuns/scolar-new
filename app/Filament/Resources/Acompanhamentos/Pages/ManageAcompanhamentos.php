<?php

namespace App\Filament\Resources\Acompanhamentos\Pages;

use App\Filament\Resources\Acompanhamentos\AcompanhamentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAcompanhamentos extends ManageRecords
{
    protected static string $resource = AcompanhamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar Acompanhamento')
                ->icon('heroicon-o-plus')
                ->modalHeading('Adicionar Acompanhamento'),
                
        ];
    }
}
