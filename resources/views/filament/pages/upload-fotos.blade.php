<x-filament-panels::page>
    <div class="space-y-8">
        <!-- Formulário de Upload -->
        <div class="max-w-2xl">
            <form wire:submit="submit" class="space-y-6">
                {{ $this->form }}

                <div class="flex justify-end gap-3 pt-4">
                    <x-filament::button
                        type="submit"
                        color="primary"
                    >
                        Enviar
                    </x-filament::button>
                </div>
            </form>
        </div>

        <!-- Tabela de Estudantes sem Foto -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                  <br>
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Lista de estudantes que ainda não possuem foto no sistema
                </p>
            </div>

            <div class="p-6">
                {{ $this->table }}
            </div>
        </div>
</x-filament-panels::page>
