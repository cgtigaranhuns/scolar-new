<?php

namespace App\Filament\Resources\Conselhos\Pages;

use App\Filament\Resources\Conselhos\ConselhoResource;
use App\Models\Discente;
use App\Models\DiscentesConselho;
use Filament\Resources\Pages\CreateRecord;

class CreateConselho extends CreateRecord
{
    protected static string $resource = ConselhoResource::class;

    protected function afterCreate(): void
    {
        $turmaId = $this->record->turma_id;

        Discente::where('turma', $turmaId)
            ->get()
            ->each(function ($discente) {
                DiscentesConselho::create([
                    'conselho_id' => $this->record->id,
                    'discente_id' => $discente->id,
                ]);
            });
    }
}