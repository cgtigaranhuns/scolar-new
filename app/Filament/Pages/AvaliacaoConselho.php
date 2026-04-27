<?php

namespace App\Filament\Pages;

use App\Models\Conselho;
use App\Models\DiscentesConselho;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AvaliacaoConselho extends Page
{
    protected static string|\BackedEnum|null $navigationIcon  = 'heroicon-o-eye';
    protected static ?string                 $title           = 'Avaliação do Conselho';
    protected string                         $view            = 'filament.pages.avaliacao-conselho';
    protected static string|\UnitEnum|null   $navigationGroup = 'Conselhos de Classe';

    // Dados do conselho exibidos no cabeçalho
    public ?int    $conselhoId = null;
    public string  $descricao  = '';
    public string  $turma      = '';
    public string  $unidade    = '';
    public string  $dataInicio = '';
    public string  $dataFim    = '';
    public string  $status     = '';

    // Lista de discentes e avaliações editáveis (indexado por DiscentesConselho.id)
    public array $discentes  = [];
    public array $avaliacoes = [];

    public function mount(): void
    {
        // A action da tabela envia o id do conselho como query param ?record=
        $recordId = request()->query('record');

        if (! $recordId) {
            $this->redirect(route('filament.admin.pages.dashboard'));
            return;
        }

        $conselho = Conselho::with('turma')->find($recordId);

        if (! $conselho) {
            Notification::make()->title('Conselho não encontrado.')->danger()->send();
            $this->redirect(route('filament.admin.pages.dashboard'));
            return;
        }

        $this->conselhoId = $conselho->id;
        $this->descricao  = $conselho->descricao ?? '';
        $this->turma      = $conselho->turma?->nome ?? '–';
        $this->unidade    = $conselho->unidade ?? '';
        $this->dataInicio = $conselho->data_inicio
            ? \Carbon\Carbon::parse($conselho->data_inicio)->format('d/m/Y')
            : '';
        $this->dataFim    = $conselho->data_fim
            ? \Carbon\Carbon::parse($conselho->data_fim)->format('d/m/Y')
            : '';
        $this->status     = $conselho->status ?? '';

        $this->carregarDiscentes();
    }

    protected function carregarDiscentes(): void
    {
        $discentes = DiscentesConselho::where('conselho_id', $this->conselhoId)
            ->with('discente')
            ->get();

        $this->discentes = $discentes->map(fn($item) => [
            'id'                       => $item->id,
            'nome'                     => $item->discente?->nome      ?? '–',
            'matricula'                => $item->discente?->matricula ?? '–',
            'foto_url'                 => $item->discente?->foto
                ? asset('storage/' . $item->discente->foto)
                : null,
            'avaliacao_geral_discente' => $item->avaliacao_geral_discente ?? '',
        ])->toArray();

        $this->avaliacoes = collect($this->discentes)
            ->mapWithKeys(fn($d) => [$d['id'] => $d['avaliacao_geral_discente']])
            ->toArray();
    }

    /**
     * Salva a avaliação geral de um único discente.
     */
    public function saveDiscente(int $discenteId): void
    {
        $registro = DiscentesConselho::find($discenteId);

        if (! $registro) {
            Notification::make()->title('Estudante não encontrado.')->danger()->send();
            return;
        }

        $registro->update([
            'avaliacao_geral_discente' => $this->avaliacoes[$discenteId] ?? null,
        ]);

        // Atualiza localmente para o badge refletir o novo estado sem reload
        foreach ($this->discentes as &$d) {
            if ($d['id'] === $discenteId) {
                $d['avaliacao_geral_discente'] = $this->avaliacoes[$discenteId] ?? null;
                break;
            }
        }
        unset($d);

        Notification::make()->title('Avaliação salva!')->success()->send();
    }
}