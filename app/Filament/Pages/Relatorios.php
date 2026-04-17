<?php

namespace App\Filament\Pages;

use App\Models\Conselho;
use App\Models\Professor;
use App\Models\Turma;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Callout;

class Relatorios extends Page
{
    protected string $view = 'filament.pages.relatorios';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios';

    protected static ?string $title = 'Relatórios';

    protected static ?string $label = 'Relatórios';

    // protected function getFooterActions(): array
    // {
    //     return [
    //         Action::make('relatorio-conselhos')
    //             ->icon('heroicon-o-user-group')
    //             ->label('Relatório de Conselhos'),
    //         Action::make('relatorio-alunos')
    //             ->icon('heroicon-o-users')
    //             ->label('Relatório de Alunos'),
    //          Action::make('relatorio-professores')
    //             ->icon('heroicon-o-user')
    //             ->label('Relatório de Professores'),
    //          Action::make('relatorio-turmas')
    //             ->icon('heroicon-o-user-group')
    //             ->label('Relatório de Turmas'),

    //     ];  


    // }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                callout::make('Relatórios')
                    ->info()
                    ->description('Selecione o tipo de relatório que deseja gerar.')
                    ->footer([
                        Action::make('relatorio-conselhos')
                            ->icon('heroicon-o-user-group')
                            ->label('Relatório de Conselhos')
                            ->schema([
                                Section::make('Filtros')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('conselho_id')
                                            ->label('Selecione o Conselho')
                                            ->options(Conselho::all()->pluck('descricao', 'id')),
                                        Select::make('turma_id')
                                            ->label('Selecione a Turma')
                                            ->options(Turma::all()->pluck('nome', 'id')),
                                        DatePicker::make('data_inicio')
                                            ->label('Data de Início'),
                                        DatePicker::make('data_fim')
                                            ->label('Data de Fim'),
                                        Select::make('unidade')
                                            ->label('Selecione a Unidade')
                                            ->options([
                                                '1ª Unidade' => '1ª Unidade',
                                                '2ª Unidade' => '2ª Unidade',
                                                '3ª Unidade' => '3ª Unidade',
                                                '4ª Unidade' => '4ª Unidade',
                                            ]),
                                        Select::make('status')
                                            ->label('Selecione o Status')
                                            ->options([
                                                'Agendado' => 'Agendado',
                                                'Liberado' => 'Liberado',
                                                'Concluído' => 'Concluído',
                                                'Cancelado' => 'Cancelado'
                                            ]),                                        
                                        Select::make('professor01_id')
                                            ->label('Professores da Área Técnica')
                                            ->options(Professor::all()->pluck('nome', 'id')),
                                        Select::make('professor02_id')
                                            ->label('Professores de Ciências da Natureza, Matemática e suas Tecnologias')
                                            ->options(Professor::all()->pluck('nome', 'id')),
                                        Select::make('professor03_id')
                                            ->label('Professor de Ciências Humanas e suas Tecnologias')
                                            ->options(Professor::all()->pluck('nome', 'id')),
                                        Select::make('professor04_id')
                                            ->label('Professores de Linguagens códigos e suas Tecnologias')
                                            ->options(Professor::all()->pluck('nome', 'id')),
                                    ])

                            ])
                            ->action(function (array $data, $livewire) {
                                $params = [];
                                if ($data['conselho_id']) {
                                    $params['conselho_id'] = $data['conselho_id'];
                                }
                                if ($data['turma_id']) {
                                    $params['turma_id'] = $data['turma_id'];
                                }
                                if ($data['data_inicio']) {
                                    $params['data_inicio'] = $data['data_inicio'];
                                }
                                if ($data['data_fim']) {
                                    $params['data_fim'] = $data['data_fim'];
                                }
                                if ($data['unidade']) {
                                    $params['unidade'] = $data['unidade'];
                                }
                                if ($data['status']) {
                                    $params['status'] = $data['status'];
                                }
                                if ($data['professor01_id']) {
                                    $params['professor01_id'] = $data['professor01_id'];
                                }
                                if ($data['professor02_id']) {
                                    $params['professor02_id'] = $data['professor02_id'];
                                }
                                if ($data['professor03_id']) {
                                    $params['professor03_id'] = $data['professor03_id'];
                                }
                                if ($data['professor04_id']) {
                                    $params['professor04_id'] = $data['professor04_id'];
                                }
                                $queryString = http_build_query($params);
                                $url = route('conselhos.pdf') . ($queryString ? ('?' . $queryString) : '');

                                $livewire->js("window.open('{$url}', '_blank')");
                            }),


                        Action::make('relatorio-alunos')
                            ->icon('heroicon-o-users')
                            ->label('Relatório de Alunos'),
                        Action::make('relatorio-professores')
                            ->icon('heroicon-o-user')
                            ->label('Relatório de Professores'),
                        Action::make('relatorio-turmas')
                            ->icon('heroicon-o-user-group')
                            ->label('Relatório de Turmas'),
                    ])
            ]);
    }
}
