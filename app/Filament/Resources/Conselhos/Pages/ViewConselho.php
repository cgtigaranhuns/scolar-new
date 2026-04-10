<?php

namespace App\Filament\Resources\Conselhos\Pages;

use App\Filament\Resources\Conselhos\ConselhoResource;
use App\Models\AreaConhecimento;
use App\Models\Professor;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ViewConselho extends ViewRecord
{
    protected static string $resource = ConselhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        // Cor dos conceitos A/B/C
        $conceitoCor = fn ($state) => match ($state) {
            'A' => 'success',
            'B' => 'warning',
            'C' => 'danger',
            default => 'gray',
        };

        // Cor do status de avaliação
        $statusCor = fn ($state) => match ($state) {
            'Finalizado' => 'success',
            default      => 'warning',
        };

        // Mapeamento área → professor responsável e nome do campo de status
        // O nome real da área vem do relacionamento professor->areaConhecimento->nome
        // mas como não temos acesso direto no infolist, resolvemos via closure no $record
        $areaTab = function (string $prefix, int $areaIndex) use ($conceitoCor, $statusCor): Tab {
            return Tab::make($prefix)
                ->label(function ($record) use ($prefix, $areaIndex): string {
                    // Busca o professor da área para pegar o nome da área de conhecimento
                    $professorField = "professor0{$areaIndex}";
                    $professor = $record->conselho->{$professorField};
                    //dd($professor);
                    $nomeArea = $professor?->areaConhecimento?->nome ?? "Área {$areaIndex}";
                    return $nomeArea;
                })
                ->badge(function ($record) use ($prefix): string {
                    $status = $record->{"status_avaliacao_{$prefix}"} ?? null;
                    return $status === 'Finalizado' ? '✓' : '○';
                })
                ->badgeColor(function ($record) use ($prefix): string {
                    $status = $record->{"status_avaliacao_{$prefix}"} ?? null;
                    return $status === 'Finalizado' ? 'success' : 'danger';
                })
                ->schema([
                    // Conceitos — 6 colunas
                    Grid::make(6)
                        ->schema([
                            TextEntry::make("nt_{$prefix}_participacao")
                                ->label('Participação')
                                ->badge()
                                ->color($conceitoCor)
                                ->placeholder('—'),

                            TextEntry::make("nt_{$prefix}_interesse")
                                ->label('Interesse')
                                ->badge()
                                ->color($conceitoCor)
                                ->placeholder('—'),

                            TextEntry::make("nt_{$prefix}_organizacao")
                                ->label('Organização')
                                ->badge()
                                ->color($conceitoCor)
                                ->placeholder('—'),

                            TextEntry::make("nt_{$prefix}_comprometimento")
                                ->label('Comprometimento')
                                ->badge()
                                ->color($conceitoCor)
                                ->placeholder('—'),

                            TextEntry::make("nt_{$prefix}_disciplina")
                                ->label('Disciplina')
                                ->badge()
                                ->color($conceitoCor)
                                ->placeholder('—'),

                            TextEntry::make("nt_{$prefix}_cooperacao")
                                ->label('Cooperação')
                                ->badge()
                                ->color($conceitoCor)
                                ->placeholder('—'),
                        ]),

                    // Observações — 3 colunas
                    Grid::make(3)
                        ->schema([
                            TextEntry::make("obs_{$prefix}_gestao")
                                ->label('Obs. para Gestão')
                                ->placeholder('—'),

                            TextEntry::make("obs_{$prefix}_pais")
                                ->label('Obs. para os Pais')
                                ->placeholder('—'),

                            TextEntry::make("info_{$prefix}_complementares")
                                ->label('Informações Complementares')
                                ->placeholder('—'),
                        ]),

                    // Status e data — 2 colunas
                    Grid::make(2)
                        ->schema([
                            TextEntry::make("status_avaliacao_{$prefix}")
                                ->label('Status da Avaliação')
                                ->badge()
                                ->color($statusCor)
                                ->placeholder('Pendente'),

                            TextEntry::make("data_avaliacao_{$prefix}")
                                ->label('Data de Avaliação')
                                ->dateTime('d/m/Y H:i')
                                ->placeholder('—'),
                        ]),
                ]);
        };

        return $schema
            ->columns(3)
            ->components([

                // ── Dados do Conselho ─────────────────────────────────────────
                Section::make('Dados do Conselho')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpan(2)
                    ->columns(3)
                    ->schema([
                        TextEntry::make('descricao')
                            ->label('Descrição')
                            ->columnSpanFull(),

                        TextEntry::make('turma.nome')
                            ->label('Turma'),

                        TextEntry::make('unidade')
                            ->label('Unidade'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Liberado'   => 'success',
                                'Finalizado' => 'info',
                                'Bloqueado'  => 'danger',
                                default      => 'gray',
                            }),

                        TextEntry::make('data_inicio')
                            ->label('Data de Início')
                            ->date('d/m/Y'),

                        TextEntry::make('data_fim')
                            ->label('Data de Fim')
                            ->date('d/m/Y'),
                    ]),

                // ── Professores ───────────────────────────────────────────────
                Section::make('Professores')
                    ->icon('heroicon-o-academic-cap')
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        TextEntry::make('professor01.nome')
                            ->label(AreaConhecimento::find(Professor::find($this->record->professor01_id)?->area_conhecimento_id)?->nome.':')
                            ->placeholder('—'),

                        TextEntry::make('professor02.nome')
                            ->label(AreaConhecimento::find(Professor::find($this->record->professor02_id)?->area_conhecimento_id)?->nome.':')
                            ->placeholder('—'),

                        TextEntry::make('professor03.nome')
                            ->label(AreaConhecimento::find(Professor::find($this->record->professor03_id)?->area_conhecimento_id)?->nome.':')
                            ->placeholder('—'),

                        TextEntry::make('professor04.nome')
                            ->label(AreaConhecimento::find(Professor::find($this->record->professor04_id)?->area_conhecimento_id)?->nome.':')
                            ->placeholder('—'),
                    ]),

                // ── Lançamentos por Estudante ──────────────────────────────────
                Section::make('Lançamentos de Conceitos por Estudante')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('discentesConselho')
                            ->hiddenLabel()
                            ->contained(true)
                            ->schema([

                                // Cabeçalho do discente
                                Grid::make(12)
                                    ->schema([
                                        ImageEntry::make('discente.foto')
                                            ->hiddenLabel()
                                            ->disk('public')
                                            ->height(56)
                                            ->width(56)
                                            ->defaultImageUrl('https://ui-avatars.com/api/?name=Discente&background=random')
                                            ->circular()
                                            ->columnSpan(1),

                                        TextEntry::make('discente.nome')
                                            ->label('Estudante')
                                            ->columnSpan(7),

                                        TextEntry::make('discente.matricula')
                                            ->label('Matrícula')
                                            ->columnSpan(2),

                                        TextEntry::make('status_geral_avaliacoes')
                                            ->label('Status Geral')
                                            ->badge()
                                            ->color(fn ($state) => match ($state) {
                                                'Finalizado' => 'success',
                                                default      => 'warning',
                                            })
                                            ->placeholder('Pendente')
                                            ->columnSpan(2),
                                    ]),

                                // Tabs por área — label e badge dinâmicos via $record
                                Tabs::make('Áreas de Conhecimento')
                                    ->tabs([
                                        $areaTab('a1', 1),
                                        $areaTab('a2', 2),
                                        $areaTab('a3', 3),
                                        $areaTab('a4', 4),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}