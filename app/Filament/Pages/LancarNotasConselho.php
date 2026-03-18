<?php

namespace App\Filament\Pages;

use App\Models\Conselho;
use App\Models\DiscentesConselho;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Image;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class LancarNotasConselho extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $title = 'Lançamento de Conceitos';
    protected string $view = 'filament.pages.lancar-notas-conselho';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filtros')
                    ->description('Selecione o conselho de classe para carregar os alunos.')
                    ->components([
                        Select::make('conselho_id')
                            ->label('Conselho')
                            ->options(Conselho::query()->pluck('descricao', 'id'))
                            ->live()
                            ->required()
                            ->searchable()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                if (!$state) {
                                    $set('lista_alunos', []);
                                    return;
                                }

                                $discentes = DiscentesConselho::where('conselho_id', $state)
                                    ->with('discente')
                                    ->get();

                                $set('lista_alunos', $discentes->map(function ($item) {
                                    return [
                                        'id'         => $item->id,
                                        'nome'       => $item->discente?->nome ?? '–',
                                        'matricula'  => $item->discente?->matricula ?? '–',
                                        // Correção na geração da URL da foto
                                        'foto_url'   => $item->discente?->foto
                                            ? asset('storage/' . $item->discente->foto)
                                            : null,
                                        'nt_participacao' => $item->nt_participacao,
                                        'nt_interesse' => $item->nt_interesse,
                                        'nt_organizacao' => $item->nt_organizacao,
                                        'nt_comprometimento' => $item->nt_comprometimento,
                                        'nt_disciplina' => $item->nt_disciplina,
                                        'nt_cooperacao' => $item->nt_cooperacao,
                                        'obs_gestao' => $item->obs_gestao,
                                        'obs_pais' => $item->obs_pais,
                                        'info_complementares' => $item->info_complementares,
                                    ];
                                })->toArray());
                            }),
                    ]),

                Section::make('Grade de Notas')
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
                                Section::make('Notas')
                                    ->components([
                                        ToggleButtons::make('nt_participacao')
                                            ->label('Participação')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ])
                                            ->inline()
                                            ->columnSpan(1),
                                        ToggleButtons::make('nt_interesse')
                                            ->label('Interesse')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ])
                                            ->inline()
                                            ->columnSpan(1),
                                        ToggleButtons::make('nt_organizacao')
                                            ->label('Organização')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ])
                                            ->inline()
                                            ->columnSpan(1),
                                        ToggleButtons::make('nt_comprometimento')
                                            ->label('Comprometimento')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ])
                                            ->inline()
                                            ->columnSpan(1),
                                        ToggleButtons::make('nt_disciplina')
                                            ->label('Disciplina')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ])
                                            ->inline()
                                            ->columnSpan(1),
                                        ToggleButtons::make('nt_cooperacao')
                                            ->label('Cooperação')
                                            ->options([
                                                'A' => 'A',
                                                'B' => 'B',
                                                'C' => 'C',
                                            ])
                                            ->inline()
                                            ->columnSpan(1),
                                    ])
                                    ->columns(6),
                                
                                Section::make('Observações')
                                    ->components([
                                Textarea::make('obs_gestao')
                                    ->label('Observação Gestão'),
                                    
                                Textarea::make('obs_pais')
                                    ->label('Observação Pais'),
                                   
                                Textarea::make('info_complementares')
                                    ->label('Informações Complementares'),
                                    ])
                                    ->columns(1),  
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('salvar')
                ->label('Salvar Alterações')
                ->color('primary')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        // dd($formData);

        if (empty($formData['lista_alunos']) || !is_array($formData['lista_alunos'])) {
            Notification::make()
                ->title('Sem dados para salvar.')
                ->warning()
                ->send();
            return;
        }

        DB::transaction(function () use ($formData) {
            foreach ($formData['lista_alunos'] as $item) {
                if (empty($item['id'])) {
                    continue;
                }

                $registro = DiscentesConselho::find($item['id']);

                if (! $registro) {
                    continue;
                }

                $registro->update([
                    'nota' => $item['nota'] ?? null,
                    'observacao' => $item['observacao'] ?? null,
                    'nt_participacao' => $item['nt_participacao'] ?? null,
                    'nt_interesse' => $item['nt_interesse'] ?? null,
                    'nt_organizacao' => $item['nt_organizacao'] ?? null,
                    'nt_comprometimento' => $item['nt_comprometimento'] ?? null,
                    'nt_disciplina' => $item['nt_disciplina'] ?? null,
                    'nt_cooperacao' => $item['nt_cooperacao'] ?? null,
                    'obs_gestao' => $item['obs_gestao'] ?? null,
                    'obs_pais' => $item['obs_pais'] ?? null,
                    'info_complementares' => $item['info_complementares'] ?? null,
                ]);
            }
        });

        Notification::make()
            ->title('Notas salvas com sucesso!')
            ->success()
            ->send();
    }
}
