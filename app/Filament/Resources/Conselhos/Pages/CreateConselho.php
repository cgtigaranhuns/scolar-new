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
        if ($this->record->unidade === '1ª Unidade' or $this->record->unidade === '3ª Unidade') {

            $turmaCodigo = $this->record->turma?->codigo;

            if (! $turmaCodigo) {
                return;
            }

            $discentes = Discente::where('turma', $turmaCodigo)->pluck('id');

            DiscentesConselho::insert(
                $discentes->map(fn($id) => [
                    'conselho_id' => $this->record->id,
                    'discente_id' => $id,
                ])->toArray()
            );
        }
    }
}
