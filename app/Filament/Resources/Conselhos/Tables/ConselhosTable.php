<?php

namespace App\Filament\Resources\Conselhos\Tables;

use App\Filament\Pages\AvaliacaoConselho;
use Filament\Actions\Action;
//use Filament\Tables\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\FlareClient\View;

class ConselhosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')
                    ->label('Conselho')
                   ->limit(70)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->sortable(),                
                TextColumn::make('data_inicio')
                    ->label('Data de Início')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('data_fim')
                    ->label('Data de Fim')
                    ->date('d/m/Y')
                    ->sortable(),
               SelectColumn::make('status')
                    ->alignCenter()
                    ->options([
                        'Agendado' => 'Agendado',
                        'Liberado' => 'Liberado',
                        'Concluído' => 'Concluído',
                        'Cancelado' => 'Cancelado'
                    ])
                    // ->icon(fn($state) => match ($state) {
                    //     'Agendado' => 'heroicon-o-calendar',
                    //     'Em andamento' => 'heroicon-o-clock',
                    //     'Concluído' => 'heroicon-o-check',
                    //     'Cancelado' => 'heroicon-o-x',
                    //     default => null,
                    // })
                    // ->colors([
                    //     'Agendado' => 'primary',
                    //     'Liberado' => 'success',
                    //     'Concluído' => 'warning',
                    //     'Cancelado' => 'danger',
                    // ])
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
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->tooltip('Editar'),
                DeleteAction::make()
                    ->label('')
                    ->tooltip('Excluir'),
                ViewAction::make()
                    ->visible(fn($record) => $record->status === 'Liberado' && ($record->unidade === '1ª Unidade' || $record->unidade === '3ª Unidade'))
                    ->label('')
                    ->tooltip('Visualizar Preenchimento do Conselho'),
                Action::make('avaliar')
                    ->visible(fn($record) => $record->status === 'Liberado' && ($record->unidade === '2ª Unidade' || $record->unidade === '4ª Unidade'))
                    ->label('')
                    ->icon('heroicon-o-check')
                    ->tooltip('Realizar Avaliação do Conselho')
                    ->action(function ($record, $livewire) {
                        $livewire->redirect(AvaliacaoConselho::getUrl(['record' => $record]));
                    }),
                    
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                  //  DeleteBulkAction::make(),
                ]),
            ]);
    }
}
