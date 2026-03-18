<?php

namespace App\Filament\Resources\Professors\Pages;

use App\Filament\Resources\Professors\ProfessorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProfessors extends ManageRecords
{
    protected static string $resource = ProfessorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
