<?php

namespace App\Filament\Resources\AreaConhecimentos\Pages;

use App\Filament\Resources\AreaConhecimentos\AreaConhecimentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAreaConhecimentos extends ManageRecords
{
    protected static string $resource = AreaConhecimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
