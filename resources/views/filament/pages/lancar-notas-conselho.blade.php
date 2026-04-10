<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        
        @if ($showSaveButton)
        <div><br></div>
            <div class="mt-6 flex justify-end gap-2 px-4 py-2">
                <x-filament::button type="submit" padding="px-6" color="primary">
                    Salvar Alterações
                </x-filament::button>

                @if ($finalizada)
                    <x-filament::button type="button" wire:click="finalize" color="success" disabled="true">                            
                        Avaliação Finalizada
                    </x-filament::button>
                @else
                    <x-filament::button type="button" wire:click="finalize" color="danger" 
                        onclick="return confirm('Tem certeza que deseja salvar e finalizar? Esta ação não poderá ser desfeita.')">
                        Salvar e Finalizar
                    </x-filament::button>
                    
                @endif

            </div>
        @endif
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
