<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            {{ $descricao }}
        </x-slot>
        
        <x-slot name="description">
            <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 14px; color: #6b7280;">
                <span><strong style="color: #374151;">Turma:</strong> {{ $turma }}</span>
                <span><strong style="color: #374151;">Unidade:</strong> {{ $unidade }}</span>
                <span><strong style="color: #374151;">Período:</strong> {{ $dataInicio }} – {{ $dataFim }}</span>
                <span>
                    <strong style="color: #374151;">Status:</strong>
                    @if($status === 'Liberado')
                        <span style="display: inline-flex; align-items: center; border-radius: 9999px; padding: 2px 8px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #166534;">{{ $status }}</span>
                    @elseif($status === 'Agendado')
                        <span style="display: inline-flex; align-items: center; border-radius: 9999px; padding: 2px 8px; font-size: 12px; font-weight: 600; background: #fef3c7; color: #92400e;">{{ $status }}</span>
                    @else
                        <span style="display: inline-flex; align-items: center; border-radius: 9999px; padding: 2px 8px; font-size: 12px; font-weight: 600; background: #f3f4f6; color: #6b7280;">{{ $status }}</span>
                    @endif
                </span>
            </div>
        </x-slot>
    </x-filament::section>

   <x-filament::section>
        <x-slot name="heading">
            <strong style="color: #db8570;">Avaliações das Áreas de Conhecimento</strong>
            <br>
            <br>
            <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 14px; color: #6b7280;">
                <span><strong style="color: #374151;">Área Técnica:</strong> {{ $avaliacao_a1 }}</span>
                <span><strong style="color: #374151;">Ciências da natureza, matemática e suas tecnologias:</strong> {{ $avaliacao_a2 }}</span>
                <span><strong style="color: #374151;">Ciências humanas e suas tecnologias:</strong> {{ $avaliacao_a3 }}</span>
                <span><strong style="color: #374151;">Linguagens códigos e suas tecnologias:</strong> {{ $avaliacao_a4 }}</span>
            </div>
        </x-slot>
    </x-filament::section>

    {{-- Contador de progresso --}}
    @php
        $total     = count($discentes);
        $avaliados = collect($discentes)->filter(fn($d) => ! empty($d['avaliacao_geral_discente']))->count();
    @endphp
    @if ($total > 0)
        <x-filament::card>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div style="flex: 1; height: 8px; border-radius: 9999px; background: #e5e7eb; overflow: hidden;">
                    <div style="height: 8px; border-radius: 9999px; background: #3b82f6; width: {{ round(($avaliados / $total) * 100) }}%;"></div>
                </div>
                <span style="white-space: nowrap; font-weight: 500; font-size: 14px; color: #6b7280;">{{ $avaliados }}/{{ $total }} avaliados</span>
            </div>
        </x-filament::card>
    @endif

    {{-- ─── Definições compartilhadas ─────────────────────────────────────── --}}
    @php
        $labelCampo = [
            'participacao'    => 'Participação',
            'interesse'       => 'Interesse',
            'organizacao'     => 'Organização',
            'comprometimento' => 'Comprometimento',
            'disciplina'      => 'Disciplina',
            'cooperacao'      => 'Cooperação',
        ];

        $corConceito = [
            'A' => ['bg' => '#dcfce7', 'color' => '#166534'],
            'B' => ['bg' => '#fef3c7', 'color' => '#92400e'],
            'C' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
        ];

        $todasAreas = [
            'a1' => 'Área Técnica',
            'a2' => 'Ciências da Natureza',
            'a3' => 'Ciências Humanas',
            'a4' => 'Linguagens',
        ];

        $todosCampos = ['participacao', 'interesse', 'organizacao', 'comprometimento', 'disciplina', 'cooperacao'];

        // Identifica se é a 4ª unidade para aplicar layout comparativo duplo
        preg_match('/\d+/', $unidade ?? '', $_uMatches);
        $numeroUnidadeAtual = isset($_uMatches[0]) ? (int) $_uMatches[0] : null;
        $modoComparativoDuplo = ($numeroUnidadeAtual === 4);
    @endphp

    {{-- Lista de estudantes --}}
    @forelse ($discentes as $discente)
        @php $id = $discente['id']; @endphp

        <x-filament::card class="mb-4">
            {{-- Cabeçalho: foto, nome, matrícula e badge --}}
            <div style="display: flex; align-items: center; gap: 16px; padding: 16px;">
                {{-- Foto --}}
                <div style="flex-shrink: 0; width: 112px; height: 112px;">
                    @if ($discente['foto_url'])
                        <img src="{{ $discente['foto_url'] }}" alt="{{ $discente['nome'] }}" style="width: 112px; height: 112px; border-radius: 50%; object-fit: cover; border: 3px solid #e5e7eb;">
                    @else
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: #f3f4f6; border: 3px solid #e5e7eb; display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 28px; height: 28px; color: #9ca3af;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Nome e matrícula --}}
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 16px; font-weight: 600; color: #111827; margin: 0;">{{ $discente['nome'] }}</p>
                    <p style="font-size: 14px; color: #6b7280; margin: 4px 0 0 0;">Matrícula: {{ $discente['matricula'] }}</p>
                </div>

                {{-- Badge de status --}}
                @if (! empty($discente['avaliacao_geral_discente']))
                    <span style="display: inline-flex; align-items: center; gap: 6px; border-radius: 9999px; padding: 6px 12px; font-size: 13px; font-weight: 600; background: #dcfce7; color: #166534;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Avaliado
                    </span>
                @else
                    <span style="display: inline-flex; align-items: center; gap: 6px; border-radius: 9999px; padding: 6px 12px; font-size: 13px; font-weight: 600; background: #fef3c7; color: #92400e;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Pendente
                    </span>
                @endif
            </div>

            {{-- ─── Bloco de conceitos anteriores ──────────────────────────────── --}}
            @php
                $comparacao = $discente['conceitos_comparacao'] ?? [];
                // Remove entradas nulas — só exibe unidades com dados reais
                $comparacaoComDados = array_filter($comparacao, fn($v) => ! empty($v));
            @endphp

            @if (! empty($comparacaoComDados))
                <div style="padding: 0 16px 16px 16px; border-top: 1px solid #e5e7eb; padding-top: 14px;">

                    @if ($modoComparativoDuplo)
                        {{-- ══════════════════════════════════════════════════════════════
                             MODO 4ª UNIDADE — cards de área lado a lado, 1ª × 3ª unidade
                             ══════════════════════════════════════════════════════════════ --}}
                        <p style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 12px 0;">
                            Evolução dos Conceitos — 1ª × 3ª Unidade
                        </p>

                        {{-- Grade: até 2 cards por linha em telas maiores, 1 em telas pequenas --}}
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @foreach ($todasAreas as $prefix => $nomeArea)
                                @php
                                    $conceitos1 = ($comparacao[1][$prefix]['conceitos'] ?? []);
                                    $conceitos3 = ($comparacao[3][$prefix]['conceitos'] ?? []);
                                    $temDados   = ! empty($conceitos1) || ! empty($conceitos3);
                                @endphp

                                @if ($temDados)
                                    <div style="flex: 1 1 calc(50% - 5px); min-width: 260px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 14px; box-sizing: border-box;">

                                        {{-- Título da área --}}
                                        <p style="font-size: 12px; font-weight: 700; color: #374151; margin: 0 0 8px 0;">
                                            {{ $nomeArea }}
                                        </p>

                                        {{-- Cabeçalho das colunas --}}
                                        <div style="display: grid; grid-template-columns: 1fr 44px 80px; gap: 4px; align-items: center; margin-bottom: 4px;">
                                            <span></span>
                                            <span style="font-size: 11px; font-weight: 700; color: #6366f1; text-align: center;">1ª Unid.</span>
                                            <span style="font-size: 11px; font-weight: 700; color: #0891b2; text-align: center;">3ª Unid.</span>
                                        </div>

                                        {{-- Linhas por campo --}}
                                        @foreach ($todosCampos as $campo)
                                            @php
                                                $v1 = $conceitos1[$campo] ?? null;
                                                $v3 = $conceitos3[$campo] ?? null;
                                                if ($v1 === null && $v3 === null) continue;

                                                $cor1 = $corConceito[$v1] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280'];
                                                $cor3 = $corConceito[$v3] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280'];

                                                $seta = ''; $setaCor = '#6b7280';
                                                if ($v1 && $v3) {
                                                    $ordem = ['A' => 1, 'B' => 2, 'C' => 3];
                                                    $o1 = $ordem[$v1] ?? 99;
                                                    $o3 = $ordem[$v3] ?? 99;
                                                    if ($o3 < $o1)     { $seta = '↑'; $setaCor = '#16a34a'; }
                                                    elseif ($o3 > $o1) { $seta = '↓'; $setaCor = '#dc2626'; }
                                                    else               { $seta = '→'; $setaCor = '#9ca3af'; }
                                                }
                                            @endphp

                                            <div style="display: grid; grid-template-columns: 1fr 44px 80px; gap: 4px; align-items: center; padding: 3px 0; border-bottom: 1px solid #f3f4f6;">
                                                <span style="font-size: 11px; color: #6b7280;">{{ $labelCampo[$campo] ?? $campo }}</span>

                                                {{-- 1ª unidade --}}
                                                <div style="display: flex; align-items: center; justify-content: center;">
                                                    @if ($v1)
                                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:9999px; font-size:12px; font-weight:700; background:{{ $cor1['bg'] }}; color:{{ $cor1['color'] }};">{{ $v1 }}</span>
                                                    @else
                                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:9999px; font-size:11px; color:#d1d5db; border:1px dashed #d1d5db;">–</span>
                                                    @endif
                                                </div>

                                                {{-- 3ª unidade + seta --}}
                                                <div style="display: flex; align-items: center; justify-content: center; gap: 3px;">
                                                    @if ($v3)
                                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:9999px; font-size:12px; font-weight:700; background:{{ $cor3['bg'] }}; color:{{ $cor3['color'] }};">{{ $v3 }}</span>
                                                    @else
                                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:9999px; font-size:11px; color:#d1d5db; border:1px dashed #d1d5db;">–</span>
                                                    @endif
                                                    @if ($seta)
                                                        <span style="font-size:13px; font-weight:700; color:{{ $setaCor }};">{{ $seta }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Legenda das setas --}}
                        <div style="display: flex; gap: 14px; margin-top: 8px; font-size: 11px; color: #9ca3af;">
                            <span><span style="color:#16a34a; font-weight:700;">↑</span> Melhorou</span>
                            <span><span style="color:#9ca3af; font-weight:700;">→</span> Manteve</span>
                            <span><span style="color:#dc2626; font-weight:700;">↓</span> Regrediu</span>
                        </div>

                    @else
                        {{-- ══════════════════════════════════════════════════════════════
                             MODO 2ª UNIDADE — cards de área lado a lado, 1ª unidade
                             ══════════════════════════════════════════════════════════════ --}}
                        <p style="font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 10px 0;">
                            Conceitos da Unidade Anterior
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @foreach ($comparacaoComDados as $numeroUnidade => $areasDoDiscente)
                                @foreach ($areasDoDiscente as $prefix => $dadosArea)
                                    <div style="flex: 1 1 calc(50% - 5px); min-width: 220px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; box-sizing: border-box;">
                                        <p style="font-size: 12px; font-weight: 600; color: #374151; margin: 0 0 8px 0;">
                                            {{ $dadosArea['area'] }}
                                        </p>
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            @foreach ($dadosArea['conceitos'] as $campo => $conceito)
                                                @php $cores = $corConceito[$conceito] ?? ['bg' => '#f3f4f6', 'color' => '#374151']; @endphp
                                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 2px 0; border-bottom: 1px solid #f3f4f6;">
                                                    <span style="font-size: 12px; color: #6b7280;">{{ $labelCampo[$campo] ?? $campo }}</span>
                                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:9999px; font-size:12px; font-weight:700; background:{{ $cores['bg'] }}; color:{{ $cores['color'] }};">{{ $conceito }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                </div>
            @endif

            {{-- Campo de avaliação --}}
            <div style="padding: 0 16px 16px 16px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; color: #6b7280; margin-bottom: 8px;">Avaliação Geral do Discente</label>
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <textarea
                        wire:model.defer="avaliacoes.{{ $id }}"
                        rows="3"
                        placeholder="Digite a avaliação geral deste estudante…"
                        style="flex: 1; border-radius: 8px; border: 1px solid #d1d5db; background: white; color: #111827; font-size: 14px; padding: 12px; resize: none; font-family: inherit;"
                    ></textarea>

                    <button
                        type="button"
                        wire:click="saveDiscente({{ $id }})"
                        wire:loading.attr="disabled"
                        wire:target="saveDiscente({{ $id }})"
                        style="display: inline-flex; align-items: center; gap: 8px; border-radius: 8px; background: #2563eb; color: white; font-size: 14px; font-weight: 600; padding: 12px 20px; border: none; cursor: pointer; white-space: nowrap;"
                    >
                        <span wire:loading.remove wire:target="saveDiscente({{ $id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Salvar
                        </span>
                        <span wire:loading wire:target="saveDiscente({{ $id }})">
                            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Salvando…
                        </span>
                    </button>
                </div>
            </div>
        </x-filament::card>
    @empty
        <x-filament::card>
            <div style="text-align: center; padding: 40px; font-size: 14px; color: #6b7280;">
                Nenhum estudante encontrado para este conselho.
            </div>
        </x-filament::card>
    @endforelse

    <x-filament-actions::modals />
</x-filament-panels::page>