<?php

namespace App\Filament\Resources\Acompanhamentos;

use App\Filament\Resources\Acompanhamentos\Pages\ManageAcompanhamentos;
use App\Models\Acompanhamento;
use App\Models\Discente;
use App\Models\Turma;
use App\Models\User;
use BackedEnum;
use Dom\Text;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;



class AcompanhamentoResource extends Resource
{
    protected static ?string $model = Acompanhamento::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static string|UnitEnum|null $navigationGroup = 'Atendimento';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('turma_id')
                    ->label('Turma')
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn(callable $set) => $set('discente_id', null))
                    ->options(Turma::pluck('nome', 'id')),

                Select::make('discente_id')
                    ->label('Estudante')
                    ->required()
                    ->searchable()
                    ->options(function (callable $get) {
                        $turmaId = $get('turma_id');

                        if (blank($turmaId)) return [];

                        // Busca o código da turma selecionada
                        $codigoTurma = Turma::find($turmaId)?->codigo;

                        if (blank($codigoTurma)) return [];

                        // Filtra discentes pelo campo 'turma' que bate com o 'codigo' da turma
                        return Discente::where('turma', $codigoTurma)
                            ->orderby('nome')
                            ->pluck('nome', 'id')
                            ->mapWithKeys(fn($nome, $id) => [
                                $id => "$nome - " . Discente::find($id)->matricula
                            ]);
                    })
                    ->disabled(fn(callable $get) => blank($get('turma_id'))),
                Select::make('user_id')
                    ->label('Responsável pelo Atendimento')
                    ->required()
                    ->default(fn() => Auth::id())
                    ->options(User::pluck('name', 'id')),

                DateTimePicker::make('data_hora')
                    ->required()
                    ->default(fn() => now())
                    ->label('Data e Hora'),
                ToggleButtons::make('tipo')
                    ->required()
                    ->inline()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                    ])
                    ->options([
                        'DEMANDA ESPONTÂNEA' => 'DEMANDA ESPONTÂNEA',
                        'PÓS CONSELHO' => 'PÓS CONSELHO',
                        'ROTINA DE ESTUDOS' => 'ROTINA DE ESTUDOS',
                        'RESPONSÁVEL' => 'RESPONSÁVEL',
                        'DAPNE' => 'DAPNE',
                        'OUTROS' => 'OUTROS',
                    ]),
                RichEditor::make('observacao')
                    ->label('Observação')
                    ->required()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('discente.nome')
                    ->label('Discente')
                    ->sortable(),
                TextColumn::make('turma.nome')
                    ->label('Turma')
                    ->sortable(),
                TextColumn::make('tipo')
                    ->label('Tipo de Atendimento')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('data_hora')
                    ->date('d/m/Y H:i')
                    ->alignCenter()
                    ->label('Data e Hora')
                    ->sortable(),

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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAcompanhamentos::route('/'),
        ];
    }
}
