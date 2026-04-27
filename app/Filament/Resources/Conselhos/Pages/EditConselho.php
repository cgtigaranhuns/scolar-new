<?php

namespace App\Filament\Resources\Conselhos\Pages;

use App\Filament\Resources\Conselhos\ConselhoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConselho extends EditRecord
{
    protected static string $resource = ConselhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                // Excluir também os discentes associados ao conselho
                ->before(function () {
                    $this->record->discentesConselho()->delete();
                }),
        ];
    }
}
