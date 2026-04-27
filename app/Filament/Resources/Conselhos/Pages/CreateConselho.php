<?php

namespace App\Filament\Resources\Conselhos\Pages;

use App\Filament\Resources\Conselhos\ConselhoResource;
use App\Models\Conselho;
use App\Models\Discente;
use App\Models\DiscentesConselho;
use Filament\Resources\Pages\CreateRecord;

class CreateConselho extends CreateRecord
{
    protected static string $resource = ConselhoResource::class;

    public function canCreateAnother(): bool
    {
        return false; // 👈 remove o botão "Criar Outro"
    }

    protected function afterCreate(): void
    {

            $this->record->status = 'Agendado';
            $this->record->save();
        
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


            // Pegar os dados dos campos avaliacao_a1, avaliacao_a2, avaliacao_a3, avaliacao_a4 do conselho da unidade anterior e preencher neste conselho
            $unidadeAnterior = match ($this->record->unidade) {
                '1ª Unidade' => null,
                '2ª Unidade' => '1ª Unidade',
                '3ª Unidade' => '2ª Unidade',
                '4ª Unidade' => '3ª Unidade',
                default => null,
            };

            if ($unidadeAnterior && $this->record->unidade == '2ª Unidade' || $this->record->unidade == '4ª Unidade') {
                $conselhoAnterior = Conselho::where('unidade', $unidadeAnterior)->first();

                if ($conselhoAnterior) {
                    $this->record->avaliacao_a1 = $conselhoAnterior->avaliacao_a1;
                    $this->record->avaliacao_a2 = $conselhoAnterior->avaliacao_a2;
                    $this->record->avaliacao_a3 = $conselhoAnterior->avaliacao_a3;
                    $this->record->avaliacao_a4 = $conselhoAnterior->avaliacao_a4;
                    $this->record->save();
                }
            }
        
    }
}
