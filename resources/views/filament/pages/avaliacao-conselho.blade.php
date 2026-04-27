<x-filament-panels::page>

    {{-- ── Cabeçalho do Conselho ─────────────────────────────────────────── --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm px-6 py-4 mb-6">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-3">
            {{ $descricao }}
        </h2>
        <div class="flex flex-wrap gap-x-8 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
            <span><span class="font-medium text-gray-700 dark:text-gray-300">Turma:</span> {{ $turma }}</span>
            <span><span class="font-medium text-gray-700 dark:text-gray-300">Unidade:</span> {{ $unidade }}</span>
            <span><span class="font-medium text-gray-700 dark:text-gray-300">Período:</span> {{ $dataInicio }} – {{ $dataFim }}</span>
            <span>
                <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ml-1',
                    'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' => $status === 'Liberado',
                    'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400' => $status === 'Agendado',
                    'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'               => ! in_array($status, ['Liberado','Agendado']),
                ])>{{ $status }}</span>
            </span>
        </div>
    </div>

    {{-- ── Contador de progresso ─────────────────────────────────────────── --}}
    @php
        $total     = count($discentes);
        $avaliados = collect($discentes)->filter(fn($d) => ! empty($d['avaliacao_geral_discente']))->count();
    @endphp
    @if ($total > 0)
        <div class="mb-4 flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
            <div class="flex-1 h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                <div
                    class="h-2 rounded-full bg-primary-500 transition-all"
                    style="width: {{ round(($avaliados / $total) * 100) }}%"
                ></div>
            </div>
            <span class="whitespace-nowrap font-medium">{{ $avaliados }}/{{ $total }} avaliados</span>
        </div>
    @endif

    {{-- ── Lista de estudantes ───────────────────────────────────────────── --}}
    @forelse ($discentes as $discente)
        @php $id = $discente['id']; @endphp

        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm mb-4 overflow-hidden">

            {{-- Linha do estudante --}}
            <div class="flex items-center gap-4 px-5 py-3">

                {{-- Foto compacta --}}
                <div class="flex-shrink-0">
                    @if ($discente['foto_url'])
                        <img
                            src="{{ $discente['foto_url'] }}"
                            alt="{{ $discente['nome'] }}"
                            class="h-11 w-11 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600"
                        >
                    @else
                        <div class="h-11 w-11 rounded-full bg-gray-100 dark:bg-gray-700 ring-2 ring-gray-200 dark:ring-gray-600 flex items-center justify-center">
                            <x-heroicon-o-user class="h-5 w-5 text-gray-400" />
                        </div>
                    @endif
                </div>

                {{-- Nome e matrícula --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate leading-tight">
                        {{ $discente['nome'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Matrícula: {{ $discente['matricula'] }}
                    </p>
                </div>

                {{-- Badge de status --}}
                @if (! empty($discente['avaliacao_geral_discente']))
                    <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-semibold text-success-700 dark:bg-success-900/30 dark:text-success-400">
                        <x-heroicon-m-check-circle class="h-3.5 w-3.5" />
                        Avaliado
                    </span>
                @else
                    <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full bg-warning-100 px-2.5 py-0.5 text-xs font-semibold text-warning-700 dark:bg-warning-900/30 dark:text-warning-400">
                        <x-heroicon-m-clock class="h-3.5 w-3.5" />
                        Pendente
                    </span>
                @endif
            </div>

            {{-- Campo de avaliação + botão salvar --}}
            <div class="px-5 pb-4 pt-1 border-t border-gray-100 dark:border-gray-700/60">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
                    Avaliação Geral do Discente
                </label>
                <div class="flex gap-2 items-start">
                    <textarea
                        wire:model.defer="avaliacoes.{{ $id }}"
                        rows="2"
                        placeholder="Digite a avaliação geral deste estudante…"
                        class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900 text-gray-900 dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-500
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               text-sm px-3 py-2 resize-none transition"
                    ></textarea>

                    <button
                        type="button"
                        wire:click="saveDiscente({{ $id }})"
                        wire:loading.attr="disabled"
                        wire:target="saveDiscente({{ $id }})"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 hover:bg-primary-700
                               focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               text-white text-xs font-semibold px-3 py-2 transition
                               disabled:opacity-60 disabled:cursor-not-allowed flex-shrink-0"
                    >
                        <span wire:loading.remove wire:target="saveDiscente({{ $id }})">
                            <x-heroicon-m-check class="h-3.5 w-3.5 inline -mt-0.5" />
                            Salvar
                        </span>
                        <span wire:loading wire:target="saveDiscente({{ $id }})">
                            <svg class="animate-spin h-3.5 w-3.5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Salvando…
                        </span>
                    </button>
                </div>
            </div>

        </div>
    @empty
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
            Nenhum estudante encontrado para este conselho.
        </div>
    @endforelse

    <x-filament-actions::modals />
</x-filament-panels::page>