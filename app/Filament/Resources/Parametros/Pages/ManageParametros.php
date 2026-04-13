<?php

namespace App\Filament\Resources\Parametros\Pages;

use App\Filament\Resources\Parametros\ParametroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageParametros extends ManageRecords
{
    protected static string $resource = ParametroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar Parâmetro')
                ->hidden(fn() => \App\Models\Parametro::count() >= 1)
                ->icon('heroicon-o-plus')
                ->modalHeading('Criar Parâmetro'),
        ];
    }
}
