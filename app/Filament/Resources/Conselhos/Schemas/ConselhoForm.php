<?php

namespace App\Filament\Resources\Conselhos\Schemas;

use App\Models\Professor;
use App\Models\Turma;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class ConselhoForm
{
   

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('descricao')
                    ->required(),
                Select::make('unidade')
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

                Select::make('professor01_id')
                    ->label('Professor de Ciências da natureza, Matemática e suas Tecnologias')
                    ->relationship('professor01', 'nome', function ($query, callable $get) {
                        $turmaId = $get('turma_id');

                        return $query->where('area_conhecimento_id', 2)
                            ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                    })

                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),

                Select::make('professor02_id')
                    ->label('Professor de Ciências Humanas e suas Tecnologias')
                    ->relationship('professor02', 'nome', function ($query, callable $get) {
                        $turmaId = $get('turma_id');

                        return $query->where('area_conhecimento_id', 3)
                            ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                    })
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->required(),

                Select::make('professor03_id')
                    ->label('Professor de Linguagens códigos e suas Tecnologias')
                    ->relationship('professor03', 'nome', function ($query, callable $get) {
                        $turmaId = $get('turma_id');

                        return $query->where('area_conhecimento_id', 4)
                            ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                    })
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->required(),

                Select::make('professor04_id')
                    ->label('Professor de Área Técnica')
                    ->relationship('professor04', 'nome', function ($query, callable $get) {
                        $turmaId = $get('turma_id');

                        return $query->where('area_conhecimento_id', 1)
                            ->when($turmaId, fn($query) => $query->whereHas('turmas', fn($query) => $query->where('turmas.id', $turmaId)));
                    })
                    ->searchable()
                    ->reactive()
                    ->preload()
                    ->required(),
                ToggleButtons::make('status')
                    ->label('Status')
                    ->default('Agendado')
                    ->inline()
                    ->options([
                        'Agendado' => 'Agendado',
                        'Liberado' => 'Liberado',                        
                        'Concluído' => 'Concluído',
                        'Cancelado' => 'Cancelado'
                    ])
                    ->icons([
                        'Agendado' => 'heroicon-o-calendar',
                        'Liberado' => 'heroicon-o-check-badge',
                        'Concluído' => 'heroicon-o-check',
                        'Cancelado' => 'heroicon-o-x-mark',
                    ])
                    ->colors([
                        'Agendado' => 'primary',
                        'Liberado' => 'success',
                        'Concluído' => 'warning',
                        'Cancelado' => 'danger',
                    ])
            ]);
    }
}
