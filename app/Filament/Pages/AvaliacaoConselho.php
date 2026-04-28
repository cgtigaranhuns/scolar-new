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

    protected static bool $shouldRegisterNavigation = false;

    // Dados do conselho exibidos no cabeçalho
    public ?int    $conselhoId = null;
    public string  $descricao  = '';
    public string  $turma      = '';
    public string  $unidade    = '';
    public string  $dataInicio = '';
    public string  $dataFim    = '';
    public string  $status     = '';
    public string  $avaliacao_a1 = '';
    public string  $avaliacao_a2 = '';
    public string  $avaliacao_a3 = '';
    public string  $avaliacao_a4 = '';

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
        $this->avaliacao_a1 = $conselho->avaliacao_a1 ?? '';
        $this->avaliacao_a2 = $conselho->avaliacao_a2 ?? '';
        $this->avaliacao_a3 = $conselho->avaliacao_a3 ?? '';
        $this->avaliacao_a4 = $conselho->avaliacao_a4 ?? '';
        $this->dataInicio = $conselho->data_inicio
            ? \Carbon\Carbon::parse($conselho->data_inicio)->format('d/m/Y')
            : '';
        $this->dataFim    = $conselho->data_fim
            ? \Carbon\Carbon::parse($conselho->data_fim)->format('d/m/Y')
            : '';
        $this->status     = $conselho->status ?? '';

        $this->carregarDiscentes();
    }

    /**
     * Retorna o número da unidade anterior à unidade do conselho atual.
     * Regra: 2ª → 1ª, 4ª → 3ª. Demais retornam null (sem unidade anterior suportada).
     */
    protected function getUnidadeAnterior(): ?int
    {
        // Normaliza: extrai o primeiro dígito da string da unidade (ex: "2", "2ª", "Unidade 2")
        preg_match('/\d+/', $this->unidade ?? '', $matches);
        $unidadeAtual = isset($matches[0]) ? (int) $matches[0] : null;

        return match ($unidadeAtual) {
            2 => 1,
            4 => 3,
            default => null,
        };
    }

    /**
     * Busca o conselho da unidade anterior para a mesma turma.
     */
    protected function getConselhoAnterior(): ?Conselho
    {
        $unidadeAnterior = $this->getUnidadeAnterior();

        if ($unidadeAnterior === null) {
            return null;
        }

        $conselho = Conselho::find($this->conselhoId);

        if (! $conselho) {
            return null;
        }

        // Busca um conselho da mesma turma cuja unidade corresponda à unidade anterior.
        // A comparação usa LIKE para lidar com formatos variados ("1", "1ª", "Unidade 1", etc.)
        return Conselho::where('turma_id', $conselho->turma_id)
            ->where('id', '!=', $this->conselhoId)
            ->where('unidade', 'LIKE', "%{$unidadeAnterior}%")
            ->latest('id')
            ->first();
    }

    protected function carregarDiscentes(): void
    {
        $discentes = DiscentesConselho::where('conselho_id', $this->conselhoId)
            ->with('discente')
            ->get();

        // Tenta carregar conceitos da unidade anterior
        $conselhoAnterior       = $this->getConselhoAnterior();
        $conceitosAnteriores    = collect();

        if ($conselhoAnterior) {
            $conceitosAnteriores = DiscentesConselho::where('conselho_id', $conselhoAnterior->id)
                ->get()
                ->keyBy(fn($item) => $item->discente_id); // indexa pelo discente_id para lookup rápido
        }

        $this->discentes = $discentes->map(function ($item) use ($conceitosAnteriores) {
            $discenteId  = $item->discente_id;
            $anterior    = $conceitosAnteriores->get($discenteId);

            return [
                'id'                       => $item->id,
                'nome'                     => $item->discente?->nome      ?? '–',
                'matricula'                => $item->discente?->matricula ?? '–',
                'foto_url'                 => $item->discente?->foto
                    ? asset('storage/' . $item->discente->foto)
                    : null,
                'avaliacao_geral_discente' => $item->avaliacao_geral_discente ?? '',

                // Conceitos da unidade anterior (null quando não há unidade anterior ou registro)
                'conceitos_anteriores'     => $anterior ? $this->extrairConceitos($anterior) : null,
            ];
        })->toArray();

        $this->avaliacoes = collect($this->discentes)
            ->mapWithKeys(fn($d) => [$d['id'] => $d['avaliacao_geral_discente']])
            ->toArray();
    }

    /**
     * Extrai todos os conceitos (A/B/C) das 4 áreas de um registro DiscentesConselho.
     */
    protected function extrairConceitos(DiscentesConselho $registro): array
    {
        $campos = ['participacao', 'interesse', 'organizacao', 'comprometimento', 'disciplina', 'cooperacao'];
        $areas  = ['a1' => 'Área Técnica', 'a2' => 'Ciências da Natureza', 'a3' => 'Ciências Humanas', 'a4' => 'Linguagens'];

        $resultado = [];

        foreach ($areas as $prefix => $nomeArea) {
            $conceitos = [];
            foreach ($campos as $campo) {
                $valor = $registro->{"nt_{$prefix}_{$campo}"};
                if ($valor !== null && $valor !== '') {
                    $conceitos[$campo] = $valor;
                }
            }
            // Só inclui a área se houver pelo menos um conceito lançado
            if (! empty($conceitos)) {
                $resultado[$prefix] = [
                    'area'     => $nomeArea,
                    'conceitos' => $conceitos,
                ];
            }
        }

        return $resultado;
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