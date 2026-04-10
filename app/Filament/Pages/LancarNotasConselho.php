<?php

namespace App\Filament\Pages;

use App\Models\Conselho;
use App\Models\DiscentesConselho;
use App\Models\Professor;
use App\Models\Turma;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class LancarNotasConselho extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $title = 'Avaliações dos Estudantes';
    protected string $view = 'filament.pages.lancar-notas-conselho';
    protected static string|\UnitEnum|null $navigationGroup = 'Conselhos de Classe';

    public array $data = [];
    public bool $showSaveButton = false;
    public bool $finalizada = false;

    // Prefixo da área do professor logado (a1, a2, a3 ou a4)
    public string $areaPrefix = 'a1';

    public function mount(): void
    {
        // Resolve o prefixo uma única vez no mount
        $professor = Professor::where('matricula', Auth::user()->username)->first();
        $areaId    = $professor?->area_conhecimento_id ?? 1;

        $this->areaPrefix = match ((int) $areaId) {
            1 => 'a1',
            2 => 'a2',
            3 => 'a3',
            4 => 'a4',
            default => 'a1',
        };

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $prefix = $this->areaPrefix;

        return $schema
            ->components([
                Section::make('Filtros')
                    ->description('Selecione o conselho de classe para carregar os alunos.')
                    ->components([
                        Select::make('conselho_id')
                            ->label('Conselho')
                            ->options(function () {
                                $professor = Professor::where('matricula', Auth::user()->username)->first();


                                if (! $professor) {
                                    return [];
                                }

                                return Conselho::where('status', 'Liberado')
                                    ->where(function ($query) use ($professor) {
                                        $query->where('professor01_id', $professor->id)
                                            ->orWhere('professor02_id', $professor->id)
                                            ->orWhere('professor03_id', $professor->id)
                                            ->orWhere('professor04_id', $professor->id);
                                    })
                                    ->pluck('descricao', 'id');
                            })
                            ->live()
                            ->required()
                            ->searchable()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) use ($prefix) {
                                if (! $state) {
                                    $set('lista_alunos', []);
                                    $this->showSaveButton = false;
                                    return;
                                }

                                $statusField = "status_avaliacao_{$prefix}";
                                $isFinalized = DiscentesConselho::where('conselho_id', $state)
                                    ->where($statusField, 'Finalizado')
                                    ->exists();
                                $this->finalizada = $isFinalized;
                                

                                $discentes = DiscentesConselho::where('conselho_id', $state)
                                    ->with('discente')
                                    ->get();

                                $set('lista_alunos', $discentes->map(function ($item) use ($prefix) {
                                    return [
                                        'id'          => $item->id,
                                        'nome'        => $item->discente?->nome ?? '–',
                                        'matricula'   => $item->discente?->matricula ?? '–',
                                        'foto_url'    => $item->discente?->foto
                                            ? asset('storage/' . $item->discente->foto)
                                            : null,
                                        // Carrega os valores já salvos para a área do professor
                                        'nt_participacao'    => $item->{"nt_{$prefix}_participacao"},
                                        'nt_interesse'       => $item->{"nt_{$prefix}_interesse"},
                                        'nt_organizacao'     => $item->{"nt_{$prefix}_organizacao"},
                                        'nt_comprometimento' => $item->{"nt_{$prefix}_comprometimento"},
                                        'nt_disciplina'      => $item->{"nt_{$prefix}_disciplina"},
                                        'nt_cooperacao'      => $item->{"nt_{$prefix}_cooperacao"},
                                        'obs_gestao'         => $item->{"obs_{$prefix}_gestao"},
                                        'obs_pais'           => $item->{"obs_{$prefix}_pais"},
                                        'info_complementares' => $item->{"info_{$prefix}_complementares"},
                                    ];
                                })->toArray());

                                $this->showSaveButton = $discentes->isNotEmpty();
                            }),
                    ]),

                Section::make('Grade de Conceitos')
                    ->description(function (Get $get) {
                        $professor   = Professor::where('matricula', Auth::user()->username)->first();
                        $area        = $professor?->areaConhecimento?->nome ?? '–';
                        $conselho    = Conselho::find($get('conselho_id') ?? 0);
                        $turma       = $conselho ? Turma::find($conselho->turma_id)?->nome ?? '–' : '–';

                        return new HtmlString(
                            '<strong>Professor:</strong> ' . e(Auth::user()->name) . '<br>' .
                                '<strong>Área de Conhecimento:</strong> ' . e($area) . '<br>' .
                                '<strong>Turma:</strong> ' . e($turma)
                        );
                    })
                    ->visible(fn(Get $get): bool => filled($get('conselho_id')))
                    ->components([
                        Repeater::make('lista_alunos')
                            ->label('Discentes da Turma')
                            ->schema([
                                Section::make('Estudante')
                                    ->components([
                                        TextInput::make('nome')
                                            ->label('Nome')
                                            ->disabled()
                                            ->columnSpan(2),
                                        TextInput::make('matricula')
                                            ->label('Matrícula')
                                            ->disabled()
                                            ->columnSpan(1),
                                        ViewField::make('foto_url')
                                            ->label('Foto')
                                            ->view('filament.forms.components.discente-foto')
                                            ->columnSpan(1),
                                    ])
                                    ->columns(4),

                                Grid::make(2)
                                    ->schema([
                                        Section::make('Notas')
                                            ->columns(2)
                                            ->components([
                                                ToggleButtons::make('nt_participacao')
                                                    ->label('Participação')
                                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C'])
                                                    ->inline()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(1),
                                                ToggleButtons::make('nt_interesse')
                                                    ->label('Interesse')
                                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C'])
                                                    ->inline()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(1),
                                                ToggleButtons::make('nt_organizacao')
                                                    ->label('Organização')
                                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C'])
                                                    ->inline()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(1),
                                                ToggleButtons::make('nt_comprometimento')
                                                    ->label('Comprometimento')
                                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C'])
                                                    ->inline()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(1),
                                                ToggleButtons::make('nt_disciplina')
                                                    ->label('Disciplina')
                                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C'])
                                                    ->inline()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(1),
                                                ToggleButtons::make('nt_cooperacao')
                                                    ->label('Cooperação')
                                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C'])
                                                    ->inline()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(1),
                                            ]),

                                        Section::make('Observações')
                                            ->components([
                                                Textarea::make('obs_gestao')
                                                    ->label('Observação para Gestão')
                                                    ->autosize()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(3),
                                                Textarea::make('obs_pais')
                                                    ->label('Observação para os Pais')
                                                    ->autosize()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(3),
                                                Textarea::make('info_complementares')
                                                    ->label('Informações Complementares')
                                                    ->autosize()
                                                    ->disabled(fn(Get $get) => $get('status_avaliacao_' . $prefix) === 'Finalizado')
                                                    ->columnSpan(3),
                                            ])
                                            ->columns(3),
                                    ]),

                                Hidden::make('id'),
                            ])

                            ->columns(1)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                            
                    ]),
            ])
            ->statePath('data');
    }   
    

    public function save(): void
    {
        $formData = $this->data;
        $prefix   = $this->areaPrefix;

        if (empty($formData['lista_alunos']) || ! is_array($formData['lista_alunos'])) {
            Notification::make()
                ->title('Sem dados para salvar.')
                ->warning()
                ->send();
            return;
        }

        DB::transaction(function () use ($formData, $prefix) {
            foreach ($formData['lista_alunos'] as $item) {
                if (empty($item['id'])) {
                    continue;
                }

                $registro = DiscentesConselho::find($item['id']);

                if (! $registro) {
                    continue;
                }

                // Salva nos campos da área do professor logado
                $registro->update([
                    "nt_{$prefix}_participacao"    => $item['nt_participacao'] ?? null,
                    "nt_{$prefix}_interesse"       => $item['nt_interesse'] ?? null,
                    "nt_{$prefix}_organizacao"     => $item['nt_organizacao'] ?? null,
                    "nt_{$prefix}_comprometimento" => $item['nt_comprometimento'] ?? null,
                    "nt_{$prefix}_disciplina"      => $item['nt_disciplina'] ?? null,
                    "nt_{$prefix}_cooperacao"      => $item['nt_cooperacao'] ?? null,
                    "obs_{$prefix}_gestao"         => $item['obs_gestao'] ?? null,
                    "obs_{$prefix}_pais"           => $item['obs_pais'] ?? null,
                    "info_{$prefix}_complementares" => $item['info_complementares'] ?? null,
                ]);
            }
        });

        Notification::make()
            ->title('Salvo com sucesso!')
            ->success()
            ->send();
    }

    public function finalize(): void
    {
       
        $formData = $this->data;
        $prefix   = $this->areaPrefix;

        if (empty($formData['lista_alunos']) || ! is_array($formData['lista_alunos'])) {
            Notification::make()
                ->title('Sem dados para finalizar.')
                ->warning()
                ->send();
            return;
        }

        $requiredFields = [
            'nt_participacao',
            'nt_interesse',
            'nt_organizacao',
            'nt_comprometimento',
            'nt_disciplina',
            'nt_cooperacao',
        ];

        $incompletos = [];

        foreach ($formData['lista_alunos'] as $item) {
            if (empty($item['id'])) {
                continue;
            }

            foreach ($requiredFields as $field) {
                $value = $item[$field] ?? null;

                if ($value === null || (is_string($value) && trim($value) === '')) {
                    $incompletos[] = $item['nome'] ?? 'Aluno sem nome';
                    break;
                }
            }
        }

        if (! empty($incompletos)) {
            $lista = implode(', ', array_unique($incompletos));

            Notification::make()
                ->title('ATENÇÃO: Não foi possível finalizar, existem campos pendentes')
                ->body('Preencha todos os conceitos antes de finalizar')
                ->persistent()
                ->danger()
                ->send();

            return;
        }
        $this->save(); // Salva antes de finalizar para garantir que as últimas alterações sejam persistidas
        DB::transaction(function () use ($formData, $prefix) {
            foreach ($formData['lista_alunos'] as $item) {
                if (empty($item['id'])) {
                    continue;
                }

                $registro = DiscentesConselho::find($item['id']);

                if (! $registro) {
                    continue;
                }

                $registro->update([
                    "status_avaliacao_{$prefix}" => 'Finalizado',
                    "data_avaliacao_{$prefix}"   => now(),
                ]);
            }
        });

        Notification::make()
            ->title('Lançamento finalizado com sucesso!')
            ->success()
            ->send();
    }
}
