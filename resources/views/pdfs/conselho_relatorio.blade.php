<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Conselhos de Classe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            box-sizing: border-box;
            font-size: 12px;
        }

        /* ─── Configurações de impressão/PDF ─────────────────────────── */
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }

            .conselho-wrapper {
                page-break-inside: auto;
                break-inside: auto;
            }

            .table-estudantes tr {
                page-break-inside: auto;
                break-inside: auto;
            }

            .observacoes-area {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .page-break-before {
                page-break-before: always;
            }
        }

        /* ─── Cabeçalho ─────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header img {
            max-width: 120px;
            height: auto;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #666;
        }

        /* ─── Resumo ─────────────────────────────────── */
        .summary {
            background-color: #ecf0f1;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            font-size: 11px;
        }

        /* ─── Tabelas gerais ─────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 7px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            padding: 5px 3px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .nowrap {
            white-space: nowrap;
        }

        /* ─── Bloco de cada Conselho ─────────────────── */
        .conselho-wrapper {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 4px;
            page-break-inside: auto;
            break-inside: auto;
        }

        /* ─── Status ─────────────────────────────────── */
        .status-concluido,
        .status-concluida,
        .status-finalizado {
            color: #27ae60;
            font-weight: bold;
        }

        .status-aberto,
        .status-em-andamento {
            color: #2980b9;
            font-weight: bold;
        }

        .status-pendente {
            color: #f39c12;
            font-weight: bold;
        }

        .status-aprovado,
        .status-aprovada {
            color: #27ae60;
            font-weight: bold;
        }

        .status-reprovado,
        .status-reprovada,
        .status-retido {
            color: #e74c3c;
            font-weight: bold;
        }

        .status-atencao {
            color: #f39c12;
            font-weight: bold;
        }

        /* ─── Área de estudantes ─────────────────────── */
        .estudantes-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 8px;
            background-color: #ecf0f1;
            border-top: 1px solid #ddd;
            font-size: 9px;
        }

        .estudantes-title {
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .legenda {
            display: flex;
            gap: 12px;
            color: #555;
        }

        .legenda strong {
            color: #2c3e50;
        }

        /* Tabela de conceitos com suporte a quebra */
        .table-estudantes {
            page-break-inside: auto;
            break-inside: auto;
            width: 100%;
        }

        .table-estudantes thead {
            display: table-header-group;
        }

        .table-estudantes tbody {
            page-break-inside: auto;
            break-inside: auto;
        }

        .table-estudantes tr {
            page-break-inside: auto;
            break-inside: auto;
        }

        .table-estudantes th {
            background-color: #34495e;
            color: #ffffff;
            font-size: 6px;
            padding: 3px 2px;
            text-align: center;
        }

        .table-estudantes th.th-nome {
            text-align: left;
        }

        .table-estudantes th.th-area {
            background-color: #2c3e50;
        }

        .table-estudantes td {
            font-size: 7px;
            padding: 3px 2px;
            text-align: center;
            color: #333;
            background-color: #ffffff;
        }

        .table-estudantes tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        .table-estudantes td.td-sep,
        .table-estudantes th.th-sep {
            border-left: 2px solid #2c3e50;
        }

        .td-nome {
            text-align: left !important;
            font-weight: bold;
        }

        /* ─── Observações e Informações Complementares ─── */
        .observacoes-linha {
            page-break-inside: auto;
            break-inside: auto;
        }

        .observacoes-area {
            background-color: #f9f9f9;
            text-align: left;
            border-top: 1px solid #ddd;
            padding: 8px 12px;
            font-size: 8px;
            page-break-inside: auto;
            break-inside: auto;
        }

        .obs-area-titulo {
            font-weight: bold;
            color: #2c3e50;
            font-size: 8.5px;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px solid #dde;
            display: block;
        }

        .obs-item {
            margin-bottom: 6px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #e0e0e0;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .obs-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* Linha label + texto em duas colunas alinhadas */
        .obs-row {
            display: table;
            width: 100%;
            margin-top: 3px;
        }

        .obs-label {
            display: table-cell;
            font-weight: bold;
            color: #2980b9;
            white-space: nowrap;
            padding-right: 8px;
            vertical-align: top;
            width: 115px;
        }

        .obs-text {
            display: table-cell;
            color: #444;
            line-height: 1.5;
            word-break: break-word;
            vertical-align: top;
            text-align: left;
        }

        .info-complementar {
            display: table;
            width: 100%;
            margin-top: 3px;
            font-style: italic;
            color: #666;
        }

        .info-complementar .obs-label {
            color: #7f8c8d;
        }

        .info-complementar .obs-text {
            color: #666;
        }

        /* ─── Rodapé ─────────────────────────────────── */
        .footer {
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .sem-obs {
            color: #999;
            font-style: italic;
        }

        .page-break {
            page-break-before: always;
        }

        /* Estilos para o ranking */
        .ranking-1 {
            color: #c0392b;
            font-weight: bold;
        }

        .ranking-2 {
            color: #e67e22;
            font-weight: bold;
        }

        .ranking-3 {
            color: #f39c12;
            font-weight: bold;
        }

        .badge-ruim {
            background-color: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }

        /* Área com fundo diferenciado */
        .area-header {
            background-color: #2c3e50 !important;
        }

        /* ─── Badges de Conceito (A / B / C) ────────── */
        .conceito-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            vertical-align: middle;
            /* Alinha a badge com os elementos de fora */
            width: 18px;
            height: 18px;
            min-width: 18px;
            padding: 0;
            line-height: 18px;
            /* Reseta heranças que empurram o texto */
            border-radius: 50%;
            font-weight: bold;
            font-size: 8px;
        }

        .conceito-a {
            background-color: #d5f5e3;
            color: #1e8449;
        }

        .conceito-b {
            background-color: #fef9e7;
            color: #b7950b;
        }

        .conceito-c {
            background-color: #fadbd8;
            color: #c0392b;
        }

        .conceito-vazio {
            color: #bbb;
        }

        /* ─── Avaliação Geral da Turma ───────────────── */
        .avaliacao-turma-wrapper {
            margin-top: 12px;
            border: 1px solid #b2c3d8;
            border-radius: 4px;
            overflow: hidden;
        }

        .avaliacao-turma-titulo {
            background-color: #2c3e50;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .avaliacao-turma-body {
            background-color: #f4f8fb;
            padding: 10px 12px;
            font-size: 8px;
        }

        .avaliacao-turma-areas {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .avaliacao-turma-area-cell {
            display: table-cell;
            width: 25%;
            padding: 6px 8px;
            border: 1px solid #cdd9e5;
            background-color: #fff;
            vertical-align: top;
        }

        .avaliacao-turma-area-label {
            font-weight: bold;
            color: #2980b9;
            font-size: 7.5px;
            display: block;
            margin-bottom: 3px;
            border-bottom: 1px solid #dde;
            padding-bottom: 2px;
        }

        .avaliacao-turma-area-texto {
            color: #333;
            line-height: 1.5;
            font-size: 7.5px;
        }

        .avaliacao-turma-geral {
            display: table;
            width: 100%;
            margin-top: 6px;
            background-color: #eaf2fb;
            border: 1px solid #aec9e0;
            border-radius: 3px;
            padding: 6px 8px;
        }

        .avaliacao-turma-geral-label {
            display: table-cell;
            font-weight: bold;
            color: #1a5276;
            white-space: nowrap;
            padding-right: 10px;
            vertical-align: top;
            font-size: 8px;
            width: 160px;
        }

        .avaliacao-turma-geral-texto {
            display: table-cell;
            color: #333;
            line-height: 1.5;
            font-size: 8px;
            vertical-align: top;
        }
    </style>
</head>

<body>

    <!-- Cabeçalho -->
    <div class="header">
        @include('layouts.cabecalho')
        <h1>Relatório de Conselhos de Classe</h1>
        <p>Turma: {{ $nomeTurma }} | Conselho: {{ $nomeConselho }} | Gerado em: {{ now()->format('d/m/Y H:i:s') }}
        </p>
    </div>

    <!-- Resumo geral -->
    @if (count($conselhos) > 0)
        <div class="summary">
            <strong>Resumo:</strong>
            Total de Conselhos: {{ count($conselhos) }} |
            Concluídos:
            {{ collect($conselhos)->filter(fn($c) => str_contains(strtolower($c->status ?? ''), 'conclu'))->count() }} |
            Em Andamento:
            {{ collect($conselhos)->filter(fn($c) => str_contains(strtolower($c->status ?? ''), 'andamento') || str_contains(strtolower($c->status ?? ''), 'aberto'))->count() }}
            |
            Pendentes:
            {{ collect($conselhos)->filter(fn($c) => str_contains(strtolower($c->status ?? ''), 'pendent'))->count() }}
        </div>
    @endif

    <!-- Conselhos -->
    @foreach ($conselhos as $conselhoIndex => $conselho)
        @php
            // Conselhos de 2ª e 4ª unidade não possuem conceitos por área,
            // apenas avaliação geral do discente.
            // Detecta pelo valor numérico dentro da string de unidade,
            // independente do formato ("a2", "2", "2ª", "2ª Unidade", etc.)
            $unidadeStr = (string) ($conselho->unidade ?? '');
            preg_match('/\d+/', $unidadeStr, $unidadeMatch);
            $unidadeNum = isset($unidadeMatch[0]) ? (int) $unidadeMatch[0] : 0;
            $isSemConceitos = in_array($unidadeNum, [2, 4]);
        @endphp
        {{-- DEBUG: unidade="{{ $conselho->unidade }}" → num={{ $unidadeNum }} → isSemConceitos={{ $isSemConceitos ? 'true' : 'false' }} --}}
        <div class="conselho-wrapper">
            <!-- Dados do Conselho -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">ID</th>
                        <th style="width: 9%;">Unidade</th>
                        <th style="width: 14%;">Turma</th>
                        <th style="width: 18%;">Descrição</th>
                        <th style="width: 10%;">Técnica</th>
                        <th style="width: 10%;">Natureza</th>
                        <th style="width: 10%;">Humanas</th>
                        <th style="width: 10%;">Linguagens</th>
                        <th style="width: 5%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" style="font-weight: bold;">{{ $conselho->id }}</td>
                        <td>{{ $conselho->unidade }}</td>
                        <td>{{ $conselho->turma->nome ?? 'N/A' }}</td>
                        <td>{{ $conselho->descricao ?? $conselho->descrição }}</td>
                        <td>{{ $conselho->professor01->nome ?? '—' }}</td>
                        <td>{{ $conselho->professor02->nome ?? '—' }}</td>
                        <td>{{ $conselho->professor03->nome ?? '—' }}</td>
                        <td>{{ $conselho->professor04->nome ?? '—' }}</td>
                        <td class="text-center status-{{ Str::slug($conselho->status ?? '') }}">
                            {{ $conselho->status }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Subtítulo + legenda -->
            @if (!$isSemConceitos)
                <div class="estudantes-header">
                    <span class="estudantes-title">Conceitos dos Estudantes</span>
                    <div class="legenda">
                        <span><strong>P</strong> Participação</span>
                        <span><strong>I</strong> Interesse</span>
                        <span><strong>O</strong> Organização</span>
                        <span><strong>C</strong> Comprometimento</span>
                        <span><strong>D</strong> Disciplina</span>
                        <span><strong>Cp</strong> Cooperação</span>
                    </div>
                </div>

                <!-- Tabela de Conceitos com 6 critérios por área -->
                @php
                    // Closure em variável evita o erro "Cannot redeclare" em loops
                    $conceitoBadge = function ($valor) {
                        $v = strtoupper(trim($valor ?? ''));
                        if ($v === '') {
                            return '<span class="conceito-vazio">—</span>';
                        }
                        $class = match ($v) {
                            'A' => 'conceito-badge conceito-a',
                            'B' => 'conceito-badge conceito-b',
                            'C' => 'conceito-badge conceito-c',
                            default => 'conceito-badge',
                        };
                        return '<span class="' . $class . '">' . e($v) . '</span>';
                    };
                @endphp
                <table class="table-estudantes">
                    <thead>
                        <tr>
                            <th rowspan="2" class="th-nome" style="width: 14%; text-align: left;">Nome do Estudante
                            </th>
                            <th colspan="6" class="th-area">Área 1 — Técnica</th>
                            <th colspan="6" class="th-area th-sep">Área 2 — Natureza</th>
                            <th colspan="6" class="th-area">Área 3 — Humanas</th>
                            <th colspan="6" class="th-area th-sep">Área 4 — Linguagens</th>
                            <th rowspan="2" style="width: 8%;">Status</th>
                        </tr>
                        <tr>
                            <th>P</th>
                            <th>I</th>
                            <th>O</th>
                            <th>C</th>
                            <th>D</th>
                            <th>Cp</th>
                            <th class="th-sep">P</th>
                            <th>I</th>
                            <th>O</th>
                            <th>C</th>
                            <th>D</th>
                            <th>Cp</th>
                            <th class="th-sep">P</th>
                            <th>I</th>
                            <th>O</th>
                            <th>C</th>
                            <th>D</th>
                            <th>Cp</th>
                            <th class="th-sep">P</th>
                            <th>I</th>
                            <th>O</th>
                            <th>C</th>
                            <th>D</th>
                            <th>Cp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conselho->discentesConselho->sortBy('discente.nome') as $item)
                            <tr>
                                <td class="td-nome">{{ $item->discente->nome ?? '—' }}</td>
                                <!-- Área 1 - Técnica -->
                                <td>{!! $conceitoBadge($item->nt_a1_participacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a1_interesse) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a1_organizacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a1_comprometimento) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a1_disciplina) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a1_cooperacao) !!}</td>
                                <!-- Área 2 - Natureza -->
                                <td class="td-sep">{!! $conceitoBadge($item->nt_a2_participacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a2_interesse) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a2_organizacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a2_comprometimento) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a2_disciplina) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a2_cooperacao) !!}</td>
                                <!-- Área 3 - Humanas -->
                                <td class="td-sep">{!! $conceitoBadge($item->nt_a3_participacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a3_interesse) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a3_organizacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a3_comprometimento) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a3_disciplina) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a3_cooperacao) !!}</td>
                                <!-- Área 4 - Linguagens -->
                                <td class="td-sep">{!! $conceitoBadge($item->nt_a4_participacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a4_interesse) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a4_organizacao) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a4_comprometimento) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a4_disciplina) !!}</td>
                                <td>{!! $conceitoBadge($item->nt_a4_cooperacao) !!}</td>
                                <td class="text-center status-{{ Str::slug($item->status_geral_avaliacoes ?? '') }}">
                                    {{ $item->status_geral_avaliacoes ?? '—' }}
                                </td>
                            </tr>

                            <!-- Observações por área (agora com 6 campos cada) -->
                            @php
                                $hasObsA1 =
                                    ($item->obs_a1_gestao ?? '') ||
                                    ($item->obs_a1_pais ?? '') ||
                                    ($item->info_a1_complementares ?? '');
                                $hasObsA2 =
                                    ($item->obs_a2_gestao ?? '') ||
                                    ($item->obs_a2_pais ?? '') ||
                                    ($item->info_a2_complementares ?? '');
                                $hasObsA3 =
                                    ($item->obs_a3_gestao ?? '') ||
                                    ($item->obs_a3_pais ?? '') ||
                                    ($item->info_a3_complementares ?? '');
                                $hasObsA4 =
                                    ($item->obs_a4_gestao ?? '') ||
                                    ($item->obs_a4_pais ?? '') ||
                                    ($item->info_a4_complementares ?? '');
                            @endphp

                            <!-- Área 1 - Técnica -->
                            @if ($hasObsA1)
                                <tr class="observacoes-linha">
                                    <td colspan="27" style="padding: 0;">
                                        <div class="observacoes-area">
                                            <div class="obs-item">
                                                <span class="obs-area-titulo">Área 1 — Técnica</span>
                                                @if ($item->obs_a1_gestao)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. de Gestão:</span>
                                                        <span class="obs-text">{{ $item->obs_a1_gestao }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->obs_a1_pais)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. aos Pais/Responsáveis:</span>
                                                        <span class="obs-text">{{ $item->obs_a1_pais }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->info_a1_complementares)
                                                    <div class="obs-row info-complementar">
                                                        <span class="obs-label">Inf. Complementares:</span>
                                                        <span
                                                            class="obs-text">{{ $item->info_a1_complementares }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Área 2 - Natureza -->
                            @if ($hasObsA2)
                                <tr class="observacoes-linha">
                                    <td colspan="27" style="padding: 0;">
                                        <div class="observacoes-area">
                                            <div class="obs-item">
                                                <span class="obs-area-titulo">Área 2 — Natureza</span>
                                                @if ($item->obs_a2_gestao)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. de Gestão:</span>
                                                        <span class="obs-text">{{ $item->obs_a2_gestao }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->obs_a2_pais)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. aos Pais/Responsáveis:</span>
                                                        <span class="obs-text">{{ $item->obs_a2_pais }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->info_a2_complementares)
                                                    <div class="obs-row info-complementar">
                                                        <span class="obs-label">Inf. Complementares:</span>
                                                        <span
                                                            class="obs-text">{{ $item->info_a2_complementares }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Área 3 - Humanas -->
                            @if ($hasObsA3)
                                <tr class="observacoes-linha">
                                    <td colspan="27" style="padding: 0;">
                                        <div class="observacoes-area">
                                            <div class="obs-item">
                                                <span class="obs-area-titulo">Área 3 — Humanas</span>
                                                @if ($item->obs_a3_gestao)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. de Gestão:</span>
                                                        <span class="obs-text">{{ $item->obs_a3_gestao }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->obs_a3_pais)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. aos Pais/Responsáveis:</span>
                                                        <span class="obs-text">{{ $item->obs_a3_pais }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->info_a3_complementares)
                                                    <div class="obs-row info-complementar">
                                                        <span class="obs-label">Inf. Complementares:</span>
                                                        <span
                                                            class="obs-text">{{ $item->info_a3_complementares }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Área 4 - Linguagens -->
                            @if ($hasObsA4)
                                <tr class="observacoes-linha">
                                    <td colspan="27" style="padding: 0;">
                                        <div class="observacoes-area">
                                            <div class="obs-item">
                                                <span class="obs-area-titulo">Área 4 — Linguagens</span>
                                                @if ($item->obs_a4_gestao)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. de Gestão:</span>
                                                        <span class="obs-text">{{ $item->obs_a4_gestao }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->obs_a4_pais)
                                                    <div class="obs-row">
                                                        <span class="obs-label">Obs. aos Pais/Responsáveis:</span>
                                                        <span class="obs-text">{{ $item->obs_a4_pais }}</span>
                                                    </div>
                                                @endif
                                                @if ($item->info_a4_complementares)
                                                    <div class="obs-row info-complementar">
                                                        <span class="obs-label">Inf. Complementares:</span>
                                                        <span
                                                            class="obs-text">{{ $item->info_a4_complementares }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="27" class="text-center">Nenhum registro encontrado para este conselho.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- ── Avaliação Geral da Turma — Conselhos 1ª e 3ª unidade (por área) ── --}}
                @php
                    // Campos esperados no model Conselho para avaliação da turma por área.
                    // Ajuste os nomes abaixo caso sejam diferentes no seu banco de dados.
                    $avalTurmaA1 = $conselho->avaliacao_turma_a1 ?? ($conselho->avaliacao_a1 ?? null);
                    $avalTurmaA2 = $conselho->avaliacao_turma_a2 ?? ($conselho->avaliacao_a2 ?? null);
                    $avalTurmaA3 = $conselho->avaliacao_turma_a3 ?? ($conselho->avaliacao_a3 ?? null);
                    $avalTurmaA4 = $conselho->avaliacao_turma_a4 ?? ($conselho->avaliacao_a4 ?? null);
                    $avalTurmaGeral = $conselho->avaliacao_turma_geral ?? ($conselho->avaliacao_geral ?? null);
                    $hasAvalTurma = $avalTurmaA1 || $avalTurmaA2 || $avalTurmaA3 || $avalTurmaA4 || $avalTurmaGeral;
                @endphp
                @if ($hasAvalTurma)
                    <div class="avaliacao-turma-wrapper">
                        <div class="avaliacao-turma-titulo">Avaliação Geral da Turma</div>
                        <div class="avaliacao-turma-body">
                            <div class="avaliacao-turma-areas">
                                <div class="avaliacao-turma-area-cell">
                                    <span class="avaliacao-turma-area-label">Área 1 — Técnica</span>
                                    <span class="avaliacao-turma-area-texto">{{ $avalTurmaA1 ?? '—' }}</span>
                                </div>
                                <div class="avaliacao-turma-area-cell">
                                    <span class="avaliacao-turma-area-label">Área 2 — Natureza</span>
                                    <span class="avaliacao-turma-area-texto">{{ $avalTurmaA2 ?? '—' }}</span>
                                </div>
                                <div class="avaliacao-turma-area-cell">
                                    <span class="avaliacao-turma-area-label">Área 3 — Humanas</span>
                                    <span class="avaliacao-turma-area-texto">{{ $avalTurmaA3 ?? '—' }}</span>
                                </div>
                                <div class="avaliacao-turma-area-cell">
                                    <span class="avaliacao-turma-area-label">Área 4 — Linguagens</span>
                                    <span class="avaliacao-turma-area-texto">{{ $avalTurmaA4 ?? '—' }}</span>
                                </div>
                            </div>
                            @if ($avalTurmaGeral)
                                <div class="avaliacao-turma-geral">
                                    <span class="avaliacao-turma-geral-label">Avaliação Geral:</span>
                                    <span class="avaliacao-turma-geral-texto">{{ $avalTurmaGeral }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                {{-- ── 2ª e 4ª Unidade: apenas Avaliação Geral do Discente ─────────── --}}
                <div class="estudantes-header">
                    <span class="estudantes-title">Avaliação Geral dos Estudantes</span>
                    <span style="font-size: 8px; color: #7f8c8d; font-style: italic;">
                        Conselho de {{ $conselho->unidade }} unidade — sem lançamento de conceitos por área
                    </span>
                </div>

                <table class="table-estudantes">
                    <thead>
                        <tr>
                            <th style="width: 35%; text-align: left;">Nome do Estudante</th>
                            <th style="width: 65%;">Avaliação Geral</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conselho->discentesConselho->sortBy('discente.nome') as $item)
                            <tr>
                                <td class="td-nome">{{ $item->discente->nome ?? '—' }}</td>
                                <td class="text-center">
                                    @php
                                        $av = $item->avaliacao_geral_discente ?? null;
                                        $corAv = match (strtoupper(trim($av ?? ''))) {
                                            'A' => 'color:#27ae60; font-weight:bold;',
                                            'B' => 'color:#e67e22; font-weight:bold;',
                                            'C' => 'color:#e74c3c; font-weight:bold;',
                                            default => 'color:#888;',
                                        };
                                    @endphp
                                    <span style="{{ $corAv }}">{{ $av ?? '—' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Nenhum registro encontrado para este conselho.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- ── Avaliação Geral da Turma — Conselhos 2ª e 4ª unidade (campo único) ── --}}
                @php
                    $avalTurmaGeral24 = $conselho->avaliacao_turma_geral ?? ($conselho->avaliacao_geral ?? null);
                @endphp
                @if ($avalTurmaGeral24)
                    <div class="avaliacao-turma-wrapper">
                        <div class="avaliacao-turma-titulo">Avaliação Geral da Turma</div>
                        <div class="avaliacao-turma-body">
                            <div class="avaliacao-turma-geral">
                                <span class="avaliacao-turma-geral-label">Avaliação Geral:</span>
                                <span class="avaliacao-turma-geral-texto">{{ $avalTurmaGeral24 }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- ========================================== -->
            <!-- RESUMO E RANKING DE CONCEITOS RUINS (C)     -->
            <!-- (apenas para conselhos de 1ª e 3ª unidade)  -->
            <!-- ========================================== -->
            @if (!$isSemConceitos)
                <div
                    style="margin-top: 20px; background-color: #fef9e6; border-left: 4px solid #e74c3c; padding: 10px; border-radius: 4px;">
                    <h4 style="margin: 0 0 8px 0; color: #c0392b;">RELATÓRIO DE CONCEITOS C (RUINS)</h4>

                    @php
                        // Contagem de conceitos C (ruins) - AGORA COM 20 CAMPOS (5 por área x 4 áreas)
                        $totalAlunos = $conselho->discentesConselho->count();
                        $alunosComConceitoCRuim = 0;
                        $totalConceitosCRuim = 0;
                        $rankingRuins = [];

                        foreach ($conselho->discentesConselho as $aluno) {
                            $contagemRuim = 0;

                            // 24 campos de conceito (6 por área)
                            $campos = [
                                // Área 1
                                $aluno->nt_a1_participacao,
                                $aluno->nt_a1_interesse,
                                $aluno->nt_a1_organizacao,
                                $aluno->nt_a1_comprometimento,
                                $aluno->nt_a1_disciplina,
                                $aluno->nt_a1_cooperacao,
                                // Área 2
                                $aluno->nt_a2_participacao,
                                $aluno->nt_a2_interesse,
                                $aluno->nt_a2_organizacao,
                                $aluno->nt_a2_comprometimento,
                                $aluno->nt_a2_disciplina,
                                $aluno->nt_a2_cooperacao,
                                // Área 3
                                $aluno->nt_a3_participacao,
                                $aluno->nt_a3_interesse,
                                $aluno->nt_a3_organizacao,
                                $aluno->nt_a3_comprometimento,
                                $aluno->nt_a3_disciplina,
                                $aluno->nt_a3_cooperacao,
                                // Área 4
                                $aluno->nt_a4_participacao,
                                $aluno->nt_a4_interesse,
                                $aluno->nt_a4_organizacao,
                                $aluno->nt_a4_comprometimento,
                                $aluno->nt_a4_disciplina,
                                $aluno->nt_a4_cooperacao,
                            ];

                            foreach ($campos as $campo) {
                                if (strtoupper(trim($campo ?? '')) === 'C') {
                                    $contagemRuim++;
                                    $totalConceitosCRuim++;
                                }
                            }

                            if ($contagemRuim > 0) {
                                $alunosComConceitoCRuim++;
                                $rankingRuins[] = [
                                    'nome' => $aluno->discente->nome ?? 'Aluno sem nome',
                                    'conceitos_ruins' => $contagemRuim,
                                    'status' => $aluno->status_geral_avaliacoes ?? '—',
                                ];
                            }
                        }

                        // Ordena ranking (mais conceitos C primeiro)
                        usort($rankingRuins, function ($a, $b) {
                            return $b['conceitos_ruins'] - $a['conceitos_ruins'];
                        });

                        $top10Ruins = array_slice($rankingRuins, 0, 10);
                        $totalPossivel = $totalAlunos * 24; // 24 conceitos por aluno
                    @endphp

                    <!-- Cards de estatísticas -->
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 15px;">
                        <div
                            style="background: #fff; padding: 8px 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <strong>Total de alunos:</strong> {{ $totalAlunos }}
                        </div>
                        <div
                            style="background: #fff; padding: 8px 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <strong>Alunos com conceito C:</strong> {{ $alunosComConceitoCRuim }}
                            ({{ $totalAlunos > 0 ? round(($alunosComConceitoCRuim / $totalAlunos) * 100, 1) : 0 }}%)
                        </div>
                        <div
                            style="background: #fff; padding: 8px 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <strong>Total de conceitos C:</strong> {{ $totalConceitosCRuim }}
                        </div>
                        <div
                            style="background: #fff; padding: 8px 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <strong>Média de C por aluno:</strong>
                            {{ $totalAlunos > 0 ? round($totalConceitosCRuim / $totalAlunos, 2) : 0 }}
                        </div>
                    </div>

                    <!-- Ranking Top 10 -->
                    @if (count($top10Ruins) > 0)
                        <div style="background: #fff; border-radius: 8px; overflow-x: auto;">
                            <h5 style="margin: 0 0 8px 0; padding: 0 5px; color: #e67e22; font-weight: bold;">RANKING
                                DOS ALUNOS COM MAIS CONCEITOS C (PIORES DESEMPENHOS)</h5>
                            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                                <thead>
                                    <tr>
                                        <th
                                            style="background-color: #c0392b; color: white; padding: 6px; text-align: center;">
                                            POSIÇÃO</th>
                                        <th
                                            style="background-color: #c0392b; color: white; padding: 6px; text-align: left;">
                                            NOME DO ALUNO</th>
                                        <th
                                            style="background-color: #c0392b; color: white; padding: 6px; text-align: center;">
                                            CONCEITOS C</th>
                                        <th
                                            style="background-color: #c0392b; color: white; padding: 6px; text-align: center;">
                                            STATUS GERAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top10Ruins as $index => $aluno)
                                        <tr style="{{ $index % 2 == 0 ? 'background-color: #f9f9f9;' : '' }}">
                                            <td
                                                style="padding: 5px; text-align: center; border-bottom: 1px solid #eee;">
                                                @if ($index == 0)
                                                    <span class="ranking-1">1º LUGAR (PIOR DESEMPENHO)</span>
                                                @elseif($index == 1)
                                                    <span class="ranking-2">2º LUGAR</span>
                                                @elseif($index == 2)
                                                    <span class="ranking-3">3º LUGAR</span>
                                                @else
                                                    <strong>{{ $index + 1 }}º LUGAR</strong>
                                                @endif
                                            </td>
                                            <td style="padding: 5px; text-align: left; border-bottom: 1px solid #eee;">
                                                <strong>{{ $aluno['nome'] }}</strong>
                                                @if ($index == 0)
                                                    <span style="color: #c0392b; font-size: 10px;"> (Atenção
                                                        prioritária!)</span>
                                                @elseif($index == 1)
                                                    <span style="color: #e67e22; font-size: 10px;"> (Necessita
                                                        acompanhamento)</span>
                                                @elseif($index == 2)
                                                    <span style="color: #f39c12; font-size: 10px;"> (Em
                                                        observação)</span>
                                                @endif
                                            </td>
                                            <td
                                                style="padding: 5px; text-align: center; border-bottom: 1px solid #eee;">
                                                <span class="badge-ruim">{{ $aluno['conceitos_ruins'] }}</span>
                                            </td>
                                            <td
                                                style="padding: 5px; text-align: center; border-bottom: 1px solid #eee;">
                                                <span class="status-{{ Str::slug($aluno['status'] ?? '') }}">
                                                    {{ $aluno['status'] ?: '—' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Destaque para o pior aluno -->
                        <div
                            style="margin-top: 12px; background-color: #fff5f5; border-left: 4px solid #e74c3c; padding: 8px; border-radius: 4px;">
                            <strong style="color: #c0392b;">ALERTA:</strong>
                            O aluno <strong>{{ $top10Ruins[0]['nome'] }}</strong> é o que apresenta o maior número de
                            conceitos C
                            ({{ $top10Ruins[0]['conceitos_ruins'] }} no total), merecendo atenção prioritária da equipe
                            pedagógica.
                        </div>
                    @else
                        <div
                            style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong>✅ PARABÉNS!</strong> Nenhum aluno com conceito C (ruim) foi registrado neste
                            conselho.
                        </div>
                    @endif

                    <!-- Gráfico de barras -->
                    @if ($totalConceitosCRuim > 0)
                        <div style="margin-top: 15px; background: #fff; padding: 10px; border-radius: 8px;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px;">
                                <small style="color: #666; font-weight: bold;">PERCENTUAL DE CONCEITOS C</small>
                                <small
                                    style="color: #888;">{{ round(($totalConceitosCRuim / $totalPossivel) * 100, 1) }}%
                                    do total de avaliações</small>
                            </div>
                            <div style="background: #ecf0f1; border-radius: 4px; height: 8px; overflow: hidden;">
                                @php
                                    $mediaPercentual = ($totalConceitosCRuim / $totalPossivel) * 100;
                                @endphp
                                <div style="background: #e74c3c; width: {{ $mediaPercentual }}%; height: 8px;"></div>
                            </div>

                        </div>
                    @endif
                </div>
            @endif

        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach

    <!-- Rodapé
    <div class="footer">
        <p>Relatório de Conselhos de Classe — Sistema Escolar — IFPE</p>
        <p style="font-size: 8px;">* Conceito C indica desempenho abaixo do esperado, necessitando de atenção pedagógica.</p>
        <p style="font-size: 7px;">* Critérios avaliados: Participação (P), Interesse (I), Organização (O), Comprometimento (C), Disciplina (D), Cooperação (Cp)</p>
    </div>
        -->

</body>

</html>
