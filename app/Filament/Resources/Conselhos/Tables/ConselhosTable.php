<?php

namespace App\Filament\Resources\Conselhos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConselhosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')
                    ->searchable(),
                TextColumn::make('turma_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('data_inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('data_fim')
                    ->date()
                    ->sortable(),
                TextColumn::make('unidade')
                    ->searchable(),
                TextColumn::make('professor01_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('professor02_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('professor03_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('professor04_id')
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
