<?php

namespace App\Filament\Resources\Conselhos\Pages;

use App\Filament\Resources\Conselhos\ConselhoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConselhos extends ListRecords
{
    protected static string $resource = ConselhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                
        ];    
    }

    
}
