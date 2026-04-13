<?php

namespace App\Filament\Pages;

use App\Models\Conselho;
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
                                    ])

                            ]),
                        Action::make('relatorio-alunos')
                            ->icon('heroicon-o-users')
                            ->label('Relatório de Alunos'),
                         Action::make('relatorio-professores')
                            ->icon('heroicon-o-user')
                            ->label('Relatório de Professores'),
                         Action::make('relatorio-turmas')
                            ->icon('heroicon-o-user-group')
                            ->label('Relatório de Turmas'),
                    ]),
            ]);
    }

    
}
