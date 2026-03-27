<?php

namespace App\Filament\Resources\Conselhos\RelationManagers;

use App\Models\Turma;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class DiscentesConselhoRelationManager extends RelationManager
{
    protected static string $relationship = 'discentesConselho';

    protected static ?string $title = 'Estudantes do Conselho';

    public function form(Schema $schema): Schema
    {
        
   // dd($this->ownerRecord->discentesConselho?->first()?->discente?->matricula);

        return $schema
            ->components([
                Fieldset::make('Estudante: ' . $this->ownerRecord->discentesConselho?->first()?->discente?->nome . ' - ' . 
                $this->ownerRecord->discentesConselho?->first()?->discente?->matricula ?? 'Estudante indifinido')                    
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Avaliação A1')
                            ->columns(3)
                            ->schema([
                                TextInput::make('nt_a1_participacao')
                                    ->label('Participação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a1_interesse')
                                    ->label('Interesse')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a1_organizacao')
                                    ->label('Organização')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a1_comprometimento')
                                    ->label('Comprometimento')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a1_disciplina')
                                    ->label('Disciplina')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a1_cooperacao')
                                    ->label('Cooperação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                Textarea::make('obs_a1_gestao')
                                    ->label('Observação Gestão')
                                    ->columnSpan(3),
                                Textarea::make('obs_a1_pais')
                                    ->label('Observação Pais')
                                    ->columnSpan(3),
                                Textarea::make('info_a1_complementares')
                                    ->label('Informações Complementares')
                                    ->columnSpan(3),
                            ]),

                        Section::make('Avaliação A2')
                            ->columns(3)
                            ->schema([
                                TextInput::make('nt_a2_participacao')
                                    ->label('Participação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a2_interesse')
                                    ->label('Interesse')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a2_organizacao')
                                    ->label('Organização')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a2_comprometimento')
                                    ->label('Comprometimento')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a2_disciplina')
                                    ->label('Disciplina')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a2_cooperacao')
                                    ->label('Cooperação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                Textarea::make('obs_a2_gestao')
                                    ->label('Observação Gestão')
                                    ->columnSpan(3),
                                Textarea::make('obs_a2_pais')
                                    ->label('Observação Pais')
                                    ->columnSpan(3),
                                Textarea::make('info_a2_complementares')
                                    ->label('Informações Complementares')
                                    ->columnSpan(3),
                            ]),

                        Section::make('Avaliação A3')
                            ->columns(3)
                            ->schema([
                                TextInput::make('nt_a3_participacao')
                                    ->label('Participação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a3_interesse')
                                    ->label('Interesse')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a3_organizacao')
                                    ->label('Organização')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a3_comprometimento')
                                    ->label('Comprometimento')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a3_disciplina')
                                    ->label('Disciplina')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a3_cooperacao')
                                    ->label('Cooperação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                Textarea::make('obs_a3_gestao')
                                    ->label('Observação Gestão')
                                    ->columnSpan(3),
                                Textarea::make('obs_a3_pais')
                                    ->label('Observação Pais')
                                    ->columnSpan(3),
                                Textarea::make('info_a3_complementares')
                                    ->label('Informações Complementares')
                                    ->columnSpan(3),
                            ]),

                        Section::make('Avaliação A4')
                            ->columns(3)
                            ->schema([
                                TextInput::make('nt_a4_participacao')
                                    ->label('Participação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a4_interesse')
                                    ->label('Interesse')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a4_organizacao')
                                    ->label('Organização')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a4_comprometimento')
                                    ->label('Comprometimento')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a4_disciplina')
                                    ->label('Disciplina')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                TextInput::make('nt_a4_cooperacao')
                                    ->label('Cooperação')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10),
                                Textarea::make('obs_a4_gestao')
                                    ->label('Observação Gestão')
                                    ->columnSpan(3),
                                Textarea::make('obs_a4_pais')
                                    ->label('Observação Pais')
                                    ->columnSpan(3),
                                Textarea::make('info_a4_complementares')
                                    ->label('Informações Complementares')
                                    ->columnSpan(3),
                            ]),

                        Section::make('Status e datas gerais')
                            ->columns(4)
                            ->schema([                               

                                Radio::make('status_avaliacao_a1')
                                    ->label('Status A1')
                                    ->options([
                                        'pendente' => 'Pendente',
                                        'ok' => 'OK',
                                    ]),
                                Radio::make('status_avaliacao_a2')
                                    ->label('Status A2')
                                    ->options([
                                        'pendente' => 'Pendente',
                                        'ok' => 'OK',
                                    ]),
                                Radio::make('status_avaliacao_a3')
                                    ->label('Status A3')
                                    ->options([
                                        'pendente' => 'Pendente',
                                        'ok' => 'OK',
                                    ]),
                                Radio::make('status_avaliacao_a4')
                                    ->label('Status A4')
                                    ->options([
                                        'pendente' => 'Pendente',
                                        'ok' => 'OK',
                                    ]),

                                DatePicker::make('data_avaliacao_a1')
                                    ->label('Data A1'),
                                DatePicker::make('data_avaliacao_a2')
                                    ->label('Data A2'),
                                DatePicker::make('data_avaliacao_a3')
                                    ->label('Data A3'),
                                DatePicker::make('data_avaliacao_a4')
                                    ->label('Data A4'),
                                Select::make('status_geral_avaliacoes')
                                    ->label('Status Geral')
                                    ->options([
                                        'pendente' => 'Pendente',
                                        'em_andamento' => 'Em andamento',
                                        'concluido' => 'Concluído',
                                    ])
                                    ->required(),
                            ])
                            ->columnSpanFull(),
                           
                        
                    ])
                    
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('conselho_id')
            ->columns([
                TextColumn::make('discente.nome')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('discente.matricula')
                    ->label('Matrícula')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
              //  CreateAction::make(),
               // AssociateAction::make(),
                // CreateAction::make('discentes-da-turma')
                //     ->label('Adicionar Estudantes por Turma')
                //     ->modalHeading('Adicionar todos os discentes da turma')
                //     ->icon('heroicon-o-user-group')
                //     ->form([
                //         Select::make('turma_id')
                //             ->label('Turma')
                //             ->options(Turma::query()->orderBy('nome')->pluck('nome', 'id'))
                //             ->searchable()
                //             ->required(),
                //     ])
                //     ->mutateFormDataUsing(function ($data) {
                //         return $data;
                //     })
                //     ->action(function ($livewire, array $data) {
                //         $turma = Turma::find($data['turma_id']);
                //         if (! $turma) {
                //             return;
                //         }

                //         $discentes = $turma->discentes;

                //         foreach ($discentes as $discente) {
                //             $livewire->ownerRecord->discentesConselho()->firstOrCreate([
                //                 'discente_id' => $discente->id,
                //             ]);
                //         }
                //     }),

            ])
            ->recordActions([
                EditAction::make(),
               // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                  //  DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
