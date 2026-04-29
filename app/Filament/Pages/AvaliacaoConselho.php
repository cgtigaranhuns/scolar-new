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
    public string  $avaliacao_geral = '';

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
        $this->avaliacao_geral = $conselho->avaliacao_geral ?? '';
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
     * Retorna o número da unidade atual (inteiro) extraído da string da unidade.
     */
    protected function getNumeroUnidadeAtual(): ?int
    {
        preg_match('/\d+/', $this->unidade ?? '', $matches);
        return isset($matches[0]) ? (int) $matches[0] : null;
    }

    /**
     * Busca um conselho da mesma turma para a unidade informada.
     */
    protected function getConselhoPorUnidade(int $numeroUnidade): ?Conselho
    {
        $conselho = Conselho::find($this->conselhoId);

        if (! $conselho) {
            return null;
        }

        return Conselho::where('turma_id', $conselho->turma_id)
            ->where('id', '!=', $this->conselhoId)
            ->where('unidade', 'LIKE', "%{$numeroUnidade}%")
            ->latest('id')
            ->first();
    }

    /**
     * Retorna as unidades anteriores que devem ser exibidas para comparação.
     *
     * Regra:
     *   2ª unidade → [1]        (apenas 1ª)
     *   4ª unidade → [1, 3]     (1ª e 3ª, para comparar evolução completa)
     *   demais     → []         (nenhuma)
     */
    protected function getUnidadesAnteriores(): array
    {
        return match ($this->getNumeroUnidadeAtual()) {
            2       => [1],
            4       => [1, 3],
            default => [],
        };
    }

    protected function carregarDiscentes(): void
    {
        $discentes = DiscentesConselho::where('conselho_id', $this->conselhoId)
            ->with('discente')
            ->get();

        // Monta mapa: número da unidade → coleção de DiscentesConselho indexada por discente_id
        $conceitosPorUnidade = [];

        foreach ($this->getUnidadesAnteriores() as $numeroUnidade) {
            $conselhoRef = $this->getConselhoPorUnidade($numeroUnidade);

            if ($conselhoRef) {
                $conceitosPorUnidade[$numeroUnidade] = DiscentesConselho::where('conselho_id', $conselhoRef->id)
                    ->get()
                    ->keyBy(fn($item) => $item->discente_id);
            }
        }

        $this->discentes = $discentes->map(function ($item) use ($conceitosPorUnidade) {
            $discenteId = $item->discente_id;

            // Para cada unidade anterior, extrai os conceitos do discente (ou null se não encontrado)
            $conceitosComparacao = [];
            foreach ($conceitosPorUnidade as $numeroUnidade => $registros) {
                $registro = $registros->get($discenteId);
                $conceitosComparacao[$numeroUnidade] = $registro
                    ? $this->extrairConceitos($registro)
                    : null;
            }

            return [
                'id'                       => $item->id,
                'nome'                     => $item->discente?->nome      ?? '–',
                'matricula'                => $item->discente?->matricula ?? '–',
                'foto_url'                 => $item->discente?->foto
                    ? asset('storage/' . $item->discente->foto)
                    : null,
                'avaliacao_geral_discente' => $item->avaliacao_geral_discente ?? '',

                /**
                 * conceitos_comparacao: array indexado pelo número da unidade anterior.
                 *
                 * Para a 2ª unidade:  [1 => [...conceitos da 1ª...]]
                 * Para a 4ª unidade:  [1 => [...conceitos da 1ª...], 3 => [...conceitos da 3ª...]]
                 * Para demais:        [] (vazio)
                 */
                'conceitos_comparacao'     => $conceitosComparacao,
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
                    'area'      => $nomeArea,
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

    /**
     * Salva a avaliação geral da turma no conselho.
     */
    public function saveAvaliacaoGeral(): void
    {
        $conselho = Conselho::find($this->conselhoId);

        if (! $conselho) {
            Notification::make()->title('Conselho não encontrado.')->danger()->send();
            return;
        }

        $conselho->update([
            'avaliacao_geral' => $this->avaliacao_geral,
        ]);

        Notification::make()->title('Avaliação geral salva!')->success()->send();
    }
}