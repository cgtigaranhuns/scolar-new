<?php

namespace App\Filament\Pages;

use App\Models\Discente;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class UploadFotos extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Importar Fotos';
    protected static ?string $label = 'Importar Fotos dos Estudantes';
    protected static string|\UnitEnum|null $navigationGroup = 'Gerenciamento';
    protected static ?string $title = 'Importar Fotos dos Estudantes';

    protected string $view = 'filament.pages.upload-fotos';

    public ?array $data = [];
    public ?array $fotos = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            TextEntry::make('instructions')
                ->label('Envie as fotos dos estudantes e garanta que os nomes dos arquivos correspondam às matrículas dos alunos (ex: 20210001.jpg).')
                ->helperText('Dica: Se os arquivos não tiverem o nome correto, eles não serão associados aos estudantes.'),




            Forms\Components\FileUpload::make('fotos')
                ->label('Carregue as fotos')
                ->helperText('Envie diversos arquivos. Os nomes das fotos serão preservados. Limite de 50 arquivos por vez, cada um com no máximo 512 KB. Formatos permitidos: JPG, PNG')
                ->multiple()
                ->acceptedFileTypes(['image/jpeg', 'image/png'])
                ->preserveFilenames()
                ->maxSize(512)
                ->disk('public')
                ->directory('fotos')
                ->visibility('public')
                ->required()
                ->maxFiles(50)
                ->previewable(false)
                ->panelLayout('list'), // <- alterado de 'grid' para 'list'    // 6 colunas no grid
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $fotos = $data['fotos'] ?? [];

        if (!is_array($fotos) || count($fotos) === 0) {
            Notification::make()
                ->title('Erro')
                ->body('Nenhuma foto foi enviada. Por favor, selecione os arquivos.')
                ->danger()
                ->send();

            return;
        }

        $updatedCount = 0;
        $notFound = [];

        foreach ($fotos as $fotoPath) {
            if (!is_string($fotoPath)) {
                continue;
            }

            $matricula = pathinfo($fotoPath, PATHINFO_FILENAME);
            $discente = Discente::where('matricula', $matricula)->first();

            if (!$discente) {
                $notFound[] = $fotoPath;
                continue;
            }

            $discente->update([
                'foto' => $fotoPath,
            ]);

            $updatedCount++;
        }

        if (count($notFound) > 0) {
            $message = "Fotos importadas: {$updatedCount}.";
            $message .= ' Alguns arquivos não foram atualizados porque não existe matrícula correspondente:';
            $message .= ' ' . implode(', ', array_map(fn($path) => pathinfo($path, PATHINFO_BASENAME), $notFound));

            Notification::make()
                ->title('Importação concluída com avisos')
                ->body($message)
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title('Importação concluída')
                ->body("Fotos importadas: {$updatedCount}.")
                ->success()
                ->send();
        }

        $this->form->fill();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Discente::whereNull('foto'))
            ->columns([
                TextColumn::make('nome')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('matricula')
                    ->searchable()
                    ->label('Matrícula'),
                TextColumn::make('turmaRelacionada.nome')
                    ->label('Turma')
                    ->searchable(),
                TextColumn::make('email_discente')
                    ->label('E-mail')
                    ->searchable(),
            ])
            ->defaultSort('nome')
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Todos os estudantes têm foto!')
            ->emptyStateDescription('Não há estudantes sem foto cadastrada.');
    }
}
