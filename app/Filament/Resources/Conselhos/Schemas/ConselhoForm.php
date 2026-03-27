<?php

namespace App\Filament\Resources\Conselhos\Schemas;

use App\Models\Professor;
use App\Models\Turma;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConselhoForm
{


    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Dados do Conselho')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('unidade')
                            ->live()
                            ->options([
                                '1ª Unidade' => '1ª Unidade',
                                '2ª Unidade' => '2ª Unidade',
                                '3ª Unidade' => '3ª Unidade',
                                '4ª Unidade' => '4ª Unidade',
                            ])
                            ->required(),
                        Select::make('turma_id')
                            ->relationship('turma', 'nome')
                            ->searchable()
                            ->live() // Dispara a atualização assim que selecionado
                            ->afterStateUpdated(function (callable $get, callable $set, ?string $state) {
                                if ($state) {
                                    $turma = \App\Models\Turma::find($state);
                                    $set('descricao', "Conselho " . $get('unidade') . " - " . $turma?->nome . " - " . date('Y'));
                                }
                            })
                            ->required(),
                        DatePicker::make('data_inicio')
                            ->required(),
                        DatePicker::make('data_fim')
                            ->required(),
                        TextInput::make('descricao')
                            ->label('Descrição do Conselho')
                            ->columnSpanFull()
                            ->readOnly()
                            ->required(),
                    ]),
                Fieldset::make('Professores Avaliadores')
                    ->visible(fn(callable $get) => in_array($get('unidade'), ['1ª Unidade', '3ª Unidade']))
                    ->columnSpanFull()
                    ->schema([
                        Select::make('professor01_id')
                            ->label('Professores da Área Técnica')
                            ->relationship('professor01', 'nome', function ($query, callable $get) {
                                $turmaId = $get('turma_id');

                                return $query->where('area_conhecimento_id', 1)
                                    ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                            })

                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->required(),

                        Select::make('professor02_id')
                            ->label('Professores de Ciências da Natureza, Matemática e suas Tecnologias')
                            ->relationship('professor02', 'nome', function ($query, callable $get) {
                                $turmaId = $get('turma_id');

                                return $query->where('area_conhecimento_id', 2)
                                    ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                            })
                            ->searchable()
                            ->reactive()
                            ->preload()
                            ->required(),

                        Select::make('professor03_id')
                            ->label('Professor de Ciências Humanas e suas Tecnologias')
                            ->relationship('professor03', 'nome', function ($query, callable $get) {
                                $turmaId = $get('turma_id');

                                return $query->where('area_conhecimento_id', 3)
                                    ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                            })
                            ->searchable()
                            ->reactive()
                            ->preload()
                            ->required(),

                        Select::make('professor04_id')
                            ->label('Professores de Linguagens códigos e suas Tecnologias')
                            ->relationship('professor04', 'nome', function ($query, callable $get) {
                                $turmaId = $get('turma_id');

                                return $query->where('area_conhecimento_id', 4)
                                    ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                            })
                            ->searchable()
                            ->reactive()
                            ->preload()
                            ->required(),
                    ]),

                Fieldset::make('Avaliação das Áreas de Conhecimento')
                    ->visible(fn(callable $get) => in_array($get('unidade'), ['2ª Unidade', '4ª Unidade']))
                    ->schema([
                        Textarea::make('avaliacao_a1')
                            ->label('Avaliacão da área técnica')
                            ->autosize(),
                        Textarea::make('avaliacao_a2')
                            ->label('Avaliacão da área de ciências da natureza, matemática e suas tecnologias')
                            ->autosize(),
                        Textarea::make('avaliacao_a3')
                            ->label('Avaliacão da área de ciências humanas e suas tecnologias')
                            ->autosize(),
                        Textarea::make('avaliacao_a4')
                            ->label('Avaliacão da área de linguagens códigos e suas tecnologias')
                            ->autosize(),
                    ])
                    ->columnSpanFull(),              
                    // ToggleButtons::make('status')
                    //         ->label('Status')
                    //         ->default('Agendado')
                    //         ->inline()                            
                    //         ->options([
                    //             'Agendado' => 'Agendado',
                    //             'Liberado' => 'Liberado',
                    //             'Concluído' => 'Concluído',
                    //             'Cancelado' => 'Cancelado'
                    //         ])
                    //         ->icons([
                    //             'Agendado' => 'heroicon-o-calendar',
                    //             'Liberado' => 'heroicon-o-check-badge',
                    //             'Concluído' => 'heroicon-o-check',
                    //             'Cancelado' => 'heroicon-o-x-mark',
                    //         ])
                    //         ->colors([
                    //             'Agendado' => 'primary',
                    //             'Liberado' => 'success',
                    //             'Concluído' => 'warning',
                    //             'Cancelado' => 'danger',
                    //         ]),
                    ])
            ->columns(2);
    }
}
