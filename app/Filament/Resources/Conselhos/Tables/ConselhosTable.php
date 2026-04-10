<?php

namespace App\Filament\Resources\Conselhos\Tables;

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
                    ->label('')
                    ->tooltip('Visualizar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                  //  DeleteBulkAction::make(),
                ]),
            ]);
    }
}
