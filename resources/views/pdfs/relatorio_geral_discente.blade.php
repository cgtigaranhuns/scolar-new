<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório Geral Discente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            box-sizing: border-box;
            font-size: 12px;
            color: #333;
        }

        /* Impressão / PDF */
        @media print {
            body { margin: 0; padding: 10px; }
            .discente-wrapper { page-break-inside: auto; break-inside: auto; }
            .page-break-before { page-break-before: always; }
        }

        /* Cabeçalho */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 { margin: 5px 0; font-size: 18px; color: #333; }
        .header p  { margin: 2px 0; font-size: 11px; color: #666; }

        /* Summary */
        .summary {
            background-color: #ecf0f1;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            font-size: 11px;
        }

        /* Tabelas gerais */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 4px 5px;
            text-align: left;
            vertical-align: top;
        }
        table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            padding: 5px;
        }

        /* Tabela de dados do conselho — fonte menor */
        .table-conselho-dados th,
        .table-conselho-dados td {
            font-size: 8px;
            padding: 3px 5px;
        }
        .table-conselho-dados .prof-lista {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .table-conselho-dados .prof-lista li {
            padding: 1px 0;
            border-bottom: 1px dashed #eee;
            font-size: 8px;
        }
        .table-conselho-dados .prof-lista li:last-child {
            border-bottom: none;
        }

        /* Status */
        .status-concluido, .status-concluida, .status-finalizado { color: #27ae60; font-weight: bold; }
        .status-aberto, .status-em-andamento                     { color: #2980b9; font-weight: bold; }
        .status-pendente                                          { color: #f39c12; font-weight: bold; }
        .status-aprovado, .status-aprovada                        { color: #27ae60; font-weight: bold; }
        .status-reprovado, .status-reprovada, .status-retido      { color: #e74c3c; font-weight: bold; }
        .status-atencao                                           { color: #f39c12; font-weight: bold; }

        /* Bloco de cada Estudante */
        .discente-wrapper {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Cabeçalho do estudante */
        .grupo-discente {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 12px;
            padding: 7px 10px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .grupo-discente span {
            font-weight: normal;
            font-size: 10px;
            color: #bdc3c7;
        }

        /* Bloco de informações do estudante */
        .info-body {
            background-color: #f4f8fb;
            padding: 8px 10px;
            font-size: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 12px;
        }

        /* Bloco de cada conselho — separação visual entre conselhos */
        .conselho-block {
            margin: 0 10px 16px 10px;
            border: 1px solid #c8d8e8;
            border-radius: 4px;
            overflow: hidden;
        }

        /* Título de destaque do conselho */
        .conselho-titulo {
            background-color: #1a5276;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            padding: 6px 10px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .conselho-titulo span {
            font-weight: normal;
            font-size: 9px;
            color: #aed6f1;
            margin-left: 10px;
            text-transform: none;
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-cell {
            display: table-cell;
            width: 25%;
            padding: 4px 8px;
            border: 1px solid #cdd9e5;
            background-color: #fff;
            vertical-align: top;
        }
        .info-cell strong {
            display: block;
            color: #2c3e50;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 2px;
            border-bottom: 1px solid #dde;
            padding-bottom: 2px;
        }

        /* Subtítulo de seção */
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
        .legenda { display: flex; gap: 12px; color: #555; }
        .legenda strong { color: #2c3e50; }

        /* Badges de Conceito (A / B / C) */
        .conceito-badge {
            display: inline-block;
            width: 18px;
            height: 18px;
            line-height: 18px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .conceito-a { background-color: #d5f5e3; color: #1e8449; }
        .conceito-b { background-color: #fef9e7; color: #b7950b; }
        .conceito-c { background-color: #fadbd8; color: #c0392b; }
        .conceito-vazio { color: #bbb; }

        /* Tabela de conceitos por área */
        .table-estudantes {
            width: 100%;
            page-break-inside: auto;
            break-inside: auto;
        }
        .table-estudantes thead { display: table-header-group; }
        .table-estudantes th {
            background-color: #34495e;
            color: #fff;
            font-size: 7px;
            padding: 3px 2px;
            text-align: center;
        }
        .table-estudantes th.th-nome { text-align: left; }
        .table-estudantes th.th-area { background-color: #2c3e50; }
        .table-estudantes td {
            font-size: 7px;
            padding: 3px 2px;
            text-align: center;
            background-color: #fff;
        }
        .table-estudantes tr:nth-child(even) td { background-color: #f2f2f2; }
        .table-estudantes td.td-sep,
        .table-estudantes th.th-sep { border-left: 2px solid #2c3e50; }
        .td-nome { text-align: left !important; font-weight: bold; }

        /* Observações */
        .observacoes-area {
            background-color: #f9f9f9;
            text-align: left;
            border-top: 1px solid #ddd;
            padding: 7px 10px;
            font-size: 8px;
        }
        .obs-area-titulo {
            font-weight: bold;
            color: #2c3e50;
            font-size: 8.5px;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #dde;
            display: block;
        }
        .obs-row { display: table; width: 100%; margin-top: 3px; }
        .obs-label {
            display: table-cell;
            font-weight: bold;
            color: #2980b9;
            white-space: nowrap;
            padding-right: 8px;
            vertical-align: top;
            width: 120px;
        }
        .obs-text {
            display: table-cell;
            color: #444;
            line-height: 1.5;
            word-break: break-word;
            vertical-align: top;
        }
        .obs-item {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #e0e0e0;
        }
        .obs-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .info-complementar { font-style: italic; color: #666; }
        .info-complementar .obs-label { color: #7f8c8d; }
        .sem-obs { color: #999; font-style: italic; }

        /* Avaliação geral da turma */
        .avaliacao-turma-wrapper {
            margin-top: 10px;
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
            padding: 8px 12px;
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
            padding: 5px 7px;
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
        .avaliacao-turma-area-texto { color: #333; line-height: 1.5; font-size: 7.5px; }
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

        /* Indicadores de Perfil do Estudante */
        .perfil-wrapper {
            margin-top: 12px;
            border: 1px solid #b2c3d8;
            border-radius: 4px;
            overflow: hidden;
        }
        .perfil-titulo {
            background-color: #1a5276;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .perfil-body {
            background-color: #f4f8fb;
            padding: 10px 12px;
        }

        /* Cards totalizadores */
        .perfil-cards {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .perfil-card {
            display: table-cell;
            width: 20%;
            padding: 6px 8px;
            border: 1px solid #cdd9e5;
            background-color: #fff;
            vertical-align: middle;
            text-align: center;
        }
        .perfil-card-num { font-size: 18px; font-weight: bold; line-height: 1.1; }
        .perfil-card-label { font-size: 7px; color: #666; text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }
        .card-a     { color: #1e8449; }
        .card-b     { color: #b7950b; }
        .card-c     { color: #c0392b; }
        .card-total { color: #2c3e50; }
        .card-pct   { color: #2980b9; }

        /* Barras de progresso — implementação via tabela (compatível com dompdf) */
        .bar-table {
            width: 100%;
            border-collapse: collapse;
            height: 12px;
            border-radius: 3px;
            overflow: hidden;
        }
        .bar-table td { height: 12px; padding: 0; border: none; }
        .bar-cell-a { background-color: #27ae60; }
        .bar-cell-b { background-color: #f39c12; }
        .bar-cell-c { background-color: #e74c3c; }
        .bar-cell-vazio { background-color: #ecf0f1; }

        /* Linha de critério */
        .perfil-criterios { margin-top: 8px; }
        .criterio-row { display: table; width: 100%; margin-bottom: 5px; }
        .criterio-label {
            display: table-cell;
            width: 110px;
            font-size: 8px;
            font-weight: bold;
            color: #2c3e50;
            vertical-align: middle;
            padding-right: 6px;
            white-space: nowrap;
        }
        .criterio-bar-wrap { display: table-cell; vertical-align: middle; }
        .criterio-resumo {
            display: table-cell;
            width: 80px;
            font-size: 7px;
            padding-left: 6px;
            vertical-align: middle;
            color: #555;
            white-space: nowrap;
        }

        /* Alertas de perfil — sem emojis, apenas marcadores textuais */
        .perfil-alerta {
            margin-top: 10px;
            padding: 7px 10px;
            border-radius: 4px;
            font-size: 8.5px;
        }
        .alerta-verde    { background-color: #d4edda; border-left: 4px solid #27ae60; color: #155724; }
        .alerta-amarelo  { background-color: #fff3cd; border-left: 4px solid #f39c12; color: #856404; }
        .alerta-vermelho { background-color: #fff5f5; border-left: 4px solid #e74c3c; color: #c0392b; }

        /* Badges de contagem */
        .badge-ruim {
            background-color: #e74c3c;
            color: white;
            padding: 1px 7px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 22px;
            text-align: center;
            font-size: 8px;
        }
        .badge-bom {
            background-color: #27ae60;
            color: white;
            padding: 1px 7px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 22px;
            text-align: center;
            font-size: 8px;
        }
        .badge-medio {
            background-color: #f39c12;
            color: white;
            padding: 1px 7px;
            border-radius: 12px;
            font-weight: bold;
            display: inline-block;
            min-width: 22px;
            text-align: center;
            font-size: 8px;
        }

        /* Acompanhamentos */
        .text-center { text-align: center; }
        .observacao-cell { font-size: 9px; color: #555; font-style: italic; }
        .table-acompanhamentos tbody tr.linha-dados:nth-child(even) td { background-color: #f9f9f9; }

        /* Quebra de página */
        .page-break { page-break-before: always; }
    </style>
</head>

<body>
    @php
        use Illuminate\Support\Str;
        use App\Models\Acompanhamento;

        /**
         * Badge de conceito (A / B / C) — sem emoji, compatível com dompdf.
         */
        $conceitoBadge = function ($valor) {
            $v = strtoupper(trim($valor ?? ''));
            if ($v === '') return '<span class="conceito-vazio">--</span>';
            $class = match ($v) {
                'A'     => 'conceito-badge conceito-a',
                'B'     => 'conceito-badge conceito-b',
                'C'     => 'conceito-badge conceito-c',
                default => 'conceito-badge',
            };
            return '<span class="' . $class . '">' . e($v) . '</span>';
        };

        /** Mapa das áreas: prefixo => nome completo */
        $areaNomes = [
            'a1' => 'A1 — Técnica',
            'a2' => 'A2 — Natureza',
            'a3' => 'A3 — Humanas',
            'a4' => 'A4 — Linguagens',
        ];
    @endphp

    <div class="header">
        @include('layouts.cabecalho')
        <h1>Relatório Geral de Estudantes</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if ($discentes->count() > 0)
        @foreach ($discentes as $discente)
        <div class="discente-wrapper">

            {{-- Cabeçalho do estudante --}}
            <div class="grupo-discente">
                {{ $discente->nome ?? '—' }}
                <span>Matrícula: {{ $discente->matricula ?? '—' }}</span>
                <span>| Turma: {{ $discente->turmaRelacionada->nome ?? $discente->turma ?? '—' }}</span>
                <span>| Status no Q-Acadêmico: {{ $discente->status_qa ?? '—' }}</span>
            </div>

            {{-- Informações básicas --}}
            <div class="info-body">
                <div class="info-grid">
                    <div class="info-cell">
                        <strong>E-mail do estudante</strong>
                        {{ $discente->email_discente ?? '—' }}
                    </div>
                    <div class="info-cell">
                        <strong>E-mail do responsável</strong>
                        {{ $discente->email_responsavel ?? '—' }}
                    </div>
                    <div class="info-cell">
                        <strong>Data de Nascimento</strong>
                        {{ optional($discente->data_nascimento)->format('d/m/Y') ?? '—' }}
                    </div>
                    <div class="info-cell">
                        <strong>Informações adicionais</strong>
                        {{ !empty($discente->informacoes_adicionais) ? strip_tags($discente->informacoes_adicionais) : '—' }}
                    </div>
                </div>
            </div>

            {{-- Conselhos e Avaliações --}}
            @php
                $conselhos = $discente->discentesConselhos->groupBy(fn($d) => $d->conselho_id);
            @endphp

            @if($conselhos->count() > 0)
                @foreach($conselhos as $conselhoId => $registrosConselho)
                    @php
                        $primeiro  = $registrosConselho->first();
                        $conselho  = $primeiro->conselho ?? null;

                        // Detecta se é conselho sem conceitos por área (2ª e 4ª unidade)
                        $unidadeStr = (string)($conselho->unidade ?? '');
                        preg_match('/\d+/', $unidadeStr, $um);
                        $unidadeNum     = isset($um[0]) ? (int)$um[0] : 0;
                        $isSemConceitos = in_array($unidadeNum, [2, 4]);
                    @endphp

                    <div class="conselho-block">

                    {{-- Título do conselho em destaque --}}
                    <div class="conselho-titulo">
                        {{ $conselho->descricao ?? 'Conselho' }}
                        <span>{{ $conselho->unidade ?? '' }}</span>
                    </div>

                    {{-- Tabela de dados do conselho — colunas variam por unidade --}}
                    @php
                        $colspanAvalGeral = $isSemConceitos ? 1 : 2;
                        $ag  = $primeiro->avaliacao_geral_discente ?? ($conselho->avaliacao_geral ?? null);
                        $agV = strtoupper(trim($ag ?? ''));
                        $agClass = match($agV) {
                            'A' => 'conceito-badge conceito-a',
                            'B' => 'conceito-badge conceito-b',
                            'C' => 'conceito-badge conceito-c',
                            default => '',
                        };
                    @endphp

                    @if(!$isSemConceitos)
                        {{-- 1ª e 3ª unidade: Professores + Status --}}
                        <table class="table-conselho-dados" style="margin-top:0;">
                            <thead>
                                <tr>
                                    <th style="width:70%">Professores</th>
                                    <th style="width:30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if($conselho)
                                            @php
                                                $profAreas = [
                                                    'professor01' => 'A1 — Técnica',
                                                    'professor02' => 'A2 — Natureza',
                                                    'professor03' => 'A3 — Humanas',
                                                    'professor04' => 'A4 — Linguagens',
                                                ];
                                            @endphp
                                            <table style="width:100%; border-collapse:collapse; margin:0; padding:0;">
                                                @foreach(array_chunk(array_keys($profAreas), 2, true) as $linhaProfs)
                                                    <tr>
                                                        @foreach($linhaProfs as $profKey)
                                                            @php $areaNome = $profAreas[$profKey]; @endphp
                                                            <td style="width:50%; padding:2px 4px; border:1px solid #eee; vertical-align:top; background:#fafafa;">
                                                                @if($conselho->$profKey)
                                                                    <span style="color:#7f8c8d; font-size:7px; display:block;">{{ $areaNome }}</span>
                                                                    <span style="font-size:8px;">{{ $conselho->$profKey->nome ?? $conselho->$profKey->name ?? '—' }}</span>
                                                                @else
                                                                    <span style="color:#7f8c8d; font-size:7px; display:block;">{{ $areaNome }}</span>
                                                                    <span style="color:#ccc; font-size:8px;">—</span>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-center status-{{ Str::slug($conselho->status ?? '') }}" style="text-align:center; vertical-align:middle; font-size:11px; font-weight:bold; letter-spacing:0.3px;">
                                        {{ $conselho->status ?? '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="background:#eaf2fb; padding:4px 8px;">
                                        <strong style="color:#1a5276; font-size:8px;">Avaliação Geral do Estudante:</strong>
                                        &nbsp;
                                        @if($agClass)
                                            <span class="{{ $agClass }}">{{ $agV }}</span>
                                        @else
                                            <span style="font-size:8px;">{{ $ag ?? '—' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        {{-- 2ª e 4ª unidade: apenas Status --}}
                        <table class="table-conselho-dados" style="margin-top:0;">
                            <thead>
                                <tr>
                                    <th style="width:100%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="status-{{ Str::slug($conselho->status ?? '') }}" style="text-align:center; vertical-align:middle; font-size:11px; font-weight:bold; letter-spacing:0.3px; padding:10px 5px;">
                                        {{ $conselho->status ?? '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background:#eaf2fb; padding:4px 8px;">
                                        <strong style="color:#1a5276; font-size:8px;">Avaliação Geral do Estudante:</strong>
                                        &nbsp;
                                        @if($agClass)
                                            <span class="{{ $agClass }}">{{ $agV }}</span>
                                        @else
                                            <span style="font-size:8px;">{{ $ag ?? '—' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    @if(!$isSemConceitos)
                        {{-- Legenda dos critérios --}}
                        <div class="estudantes-header">
                            <span class="estudantes-title">Conceitos por Área</span>
                            <div class="legenda">
                                <span><strong>P</strong> Participação</span>
                                <span><strong>I</strong> Interesse</span>
                                <span><strong>O</strong> Organização</span>
                                <span><strong>C</strong> Comprometimento</span>
                                <span><strong>D</strong> Disciplina</span>
                                <span><strong>Cp</strong> Cooperação</span>
                            </div>
                        </div>

                        {{--
                            Tabela de conceitos: 1 linha por registro,
                            4 grupos de colunas (uma por area).
                            O @for foi removido — cada registro gera apenas 1 linha.
                        --}}
                        <table class="table-estudantes">
                            <thead>
                                <tr>
                                    <th colspan="6" class="th-area">A1 — Técnica</th>
                                    <th colspan="6" class="th-area th-sep">A2 — Natureza</th>
                                    <th colspan="6" class="th-area">A3 — Humanas</th>
                                    <th colspan="6" class="th-area th-sep">A4 — Linguagens</th>
                                </tr>
                                <tr>
                                    <th>P</th><th>I</th><th>O</th><th>C</th><th>D</th><th>Cp</th>
                                    <th class="th-sep">P</th><th>I</th><th>O</th><th>C</th><th>D</th><th>Cp</th>
                                    <th class="th-sep">P</th><th>I</th><th>O</th><th>C</th><th>D</th><th>Cp</th>
                                    <th class="th-sep">P</th><th>I</th><th>O</th><th>C</th><th>D</th><th>Cp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrosConselho as $registro)
                                    {{-- 1 única linha com os conceitos das 4 areas --}}
                                    <tr>
                                        {{-- A1 — Técnica --}}
                                        <td>{!! $conceitoBadge($registro->nt_a1_participacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a1_interesse) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a1_organizacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a1_comprometimento) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a1_disciplina) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a1_cooperacao) !!}</td>
                                        {{-- A2 — Natureza --}}
                                        <td class="td-sep">{!! $conceitoBadge($registro->nt_a2_participacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a2_interesse) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a2_organizacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a2_comprometimento) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a2_disciplina) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a2_cooperacao) !!}</td>
                                        {{-- A3 — Humanas --}}
                                        <td class="td-sep">{!! $conceitoBadge($registro->nt_a3_participacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a3_interesse) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a3_organizacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a3_comprometimento) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a3_disciplina) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a3_cooperacao) !!}</td>
                                        {{-- A4 — Linguagens --}}
                                        <td class="td-sep">{!! $conceitoBadge($registro->nt_a4_participacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a4_interesse) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a4_organizacao) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a4_comprometimento) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a4_disciplina) !!}</td>
                                        <td>{!! $conceitoBadge($registro->nt_a4_cooperacao) !!}</td>
                                    </tr>

                                    {{-- Observacoes por area --}}
                                    @php
                                        $obsAreas = [
                                            ['A1 — Técnica',    $registro->obs_a1_gestao ?? '', $registro->obs_a1_pais ?? '', $registro->info_a1_complementares ?? ''],
                                            ['A2 — Natureza',   $registro->obs_a2_gestao ?? '', $registro->obs_a2_pais ?? '', $registro->info_a2_complementares ?? ''],
                                            ['A3 — Humanas',    $registro->obs_a3_gestao ?? '', $registro->obs_a3_pais ?? '', $registro->info_a3_complementares ?? ''],
                                            ['A4 — Linguagens', $registro->obs_a4_gestao ?? '', $registro->obs_a4_pais ?? '', $registro->info_a4_complementares ?? ''],
                                        ];
                                    @endphp

                                    @foreach($obsAreas as [$areaTitulo, $obsGestao, $obsPais, $obsCompl])
                                        @if($obsGestao || $obsPais || $obsCompl)
                                            <tr>
                                                <td colspan="24" style="padding:0;">
                                                    <div class="observacoes-area">
                                                        <div class="obs-item">
                                                            <span class="obs-area-titulo">{{ $areaTitulo }}</span>
                                                            @if($obsGestao)
                                                                <div class="obs-row">
                                                                    <span class="obs-label">Obs. de Gestão:</span>
                                                                    <span class="obs-text">{{ strip_tags($obsGestao) }}</span>
                                                                </div>
                                                            @endif
                                                            @if($obsPais)
                                                                <div class="obs-row">
                                                                    <span class="obs-label">Obs. aos Pais/Responsáveis:</span>
                                                                    <span class="obs-text">{{ strip_tags($obsPais) }}</span>
                                                                </div>
                                                            @endif
                                                            @if($obsCompl)
                                                                <div class="obs-row info-complementar">
                                                                    <span class="obs-label">Inf. Complementares:</span>
                                                                    <span class="obs-text">{{ strip_tags($obsCompl) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                @endforeach
                            </tbody>
                        </table>

                        {{-- Indicadores de Perfil do Estudante --}}
                        @php
                            $todosConceitos       = [];
                            $conceitosPorCriterio = [
                                'Participação'    => [],
                                'Interesse'       => [],
                                'Organização'     => [],
                                'Comprometimento' => [],
                                'Disciplina'      => [],
                                'Cooperação'      => [],
                            ];

                            foreach ($registrosConselho as $reg) {
                                foreach (['a1','a2','a3','a4'] as $area) {
                                    $mapCampos = [
                                        'Participação'    => "nt_{$area}_participacao",
                                        'Interesse'       => "nt_{$area}_interesse",
                                        'Organização'     => "nt_{$area}_organizacao",
                                        'Comprometimento' => "nt_{$area}_comprometimento",
                                        'Disciplina'      => "nt_{$area}_disciplina",
                                        'Cooperação'      => "nt_{$area}_cooperacao",
                                    ];
                                    foreach ($mapCampos as $criterio => $campo) {
                                        $val = strtoupper(trim($reg->$campo ?? ''));
                                        if (in_array($val, ['A','B','C'])) {
                                            $todosConceitos[]                  = $val;
                                            $conceitosPorCriterio[$criterio][] = $val;
                                        }
                                    }
                                }
                            }

                            $totalGeral = count($todosConceitos);
                            $totalA     = count(array_filter($todosConceitos, fn($v) => $v === 'A'));
                            $totalB     = count(array_filter($todosConceitos, fn($v) => $v === 'B'));
                            $totalC     = count(array_filter($todosConceitos, fn($v) => $v === 'C'));

                            $pctA = $totalGeral > 0 ? round(($totalA / $totalGeral) * 100, 1) : 0;
                            $pctB = $totalGeral > 0 ? round(($totalB / $totalGeral) * 100, 1) : 0;
                            $pctC = $totalGeral > 0 ? round(($totalC / $totalGeral) * 100, 1) : 0;

                            $nivelAlerta = 'verde';
                            if ($pctC >= 40)      $nivelAlerta = 'vermelho';
                            elseif ($pctC >= 20)  $nivelAlerta = 'amarelo';

                            $criterioMaisFragil = null; $maxC = -1;
                            $criterioMaisForte  = null; $maxA2 = -1;
                            foreach ($conceitosPorCriterio as $crit => $vals) {
                                $qC = count(array_filter($vals, fn($v) => $v === 'C'));
                                $qA = count(array_filter($vals, fn($v) => $v === 'A'));
                                if ($qC > $maxC)  { $maxC  = $qC; $criterioMaisFragil = $crit; }
                                if ($qA > $maxA2) { $maxA2 = $qA; $criterioMaisForte  = $crit; }
                            }
                        @endphp

                        @if($totalGeral > 0)
                        <div class="perfil-wrapper">
                            <div class="perfil-titulo">Indicadores de Perfil do Estudante — Conceitos neste Conselho</div>
                            <div class="perfil-body">

                                {{-- Cards totalizadores --}}
                                <div class="perfil-cards">
                                    <div class="perfil-card">
                                        <div class="perfil-card-num card-total">{{ $totalGeral }}</div>
                                        <div class="perfil-card-label">Total de conceitos</div>
                                    </div>
                                    <div class="perfil-card">
                                        <div class="perfil-card-num card-a">{{ $totalA }}</div>
                                        <div class="perfil-card-label">Conceito A ({{ $pctA }}%)</div>
                                    </div>
                                    <div class="perfil-card">
                                        <div class="perfil-card-num card-b">{{ $totalB }}</div>
                                        <div class="perfil-card-label">Conceito B ({{ $pctB }}%)</div>
                                    </div>
                                    <div class="perfil-card">
                                        <div class="perfil-card-num card-c">{{ $totalC }}</div>
                                        <div class="perfil-card-label">Conceito C ({{ $pctC }}%)</div>
                                    </div>
                                    <div class="perfil-card">
                                        <div class="perfil-card-num card-pct">{{ $pctA }}%</div>
                                        <div class="perfil-card-label">Aproveitamento geral</div>
                                    </div>
                                </div>

                                {{-- Barra geral A/B/C --}}
                                <div style="margin-bottom:10px;">
                                    <div style="font-size:7.5px; font-weight:bold; color:#2c3e50; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.3px;">
                                        Distribuição geral dos conceitos
                                    </div>
                                    <table class="bar-table">
                                        <tr>
                                            @if($pctA > 0)<td class="bar-cell-a" style="width:{{ $pctA }}%;"></td>@endif
                                            @if($pctB > 0)<td class="bar-cell-b" style="width:{{ $pctB }}%;"></td>@endif
                                            @if($pctC > 0)<td class="bar-cell-c" style="width:{{ $pctC }}%;"></td>@endif
                                            @if($pctA == 0 && $pctB == 0 && $pctC == 0)<td class="bar-cell-vazio" style="width:100%;"></td>@endif
                                        </tr>
                                    </table>
                                    <div style="font-size:7px; margin-top:2px; color:#555;">
                                        <span style="color:#1e8449;">[A] {{ $pctA }}%</span>&nbsp;&nbsp;
                                        <span style="color:#b7950b;">[B] {{ $pctB }}%</span>&nbsp;&nbsp;
                                        <span style="color:#c0392b;">[C] {{ $pctC }}%</span>
                                    </div>
                                </div>

                                {{-- Barras por critério --}}
                                <div style="font-size:7.5px; font-weight:bold; color:#2c3e50; margin-bottom:5px; text-transform:uppercase; letter-spacing:0.3px;">
                                    Distribuição por critério de avaliação
                                </div>
                                <div class="perfil-criterios">
                                    @foreach($conceitosPorCriterio as $criterio => $vals)
                                        @php
                                            $tot = count($vals);
                                            $qa  = count(array_filter($vals, fn($v) => $v === 'A'));
                                            $qb  = count(array_filter($vals, fn($v) => $v === 'B'));
                                            $qc  = count(array_filter($vals, fn($v) => $v === 'C'));
                                            $pa  = $tot > 0 ? round(($qa/$tot)*100) : 0;
                                            $pb  = $tot > 0 ? round(($qb/$tot)*100) : 0;
                                            $pc  = $tot > 0 ? round(($qc/$tot)*100) : 0;
                                            // Garante que A+B+C = 100 (ajuste de arredondamento no maior)
                                            $soma = $pa + $pb + $pc;
                                            if ($tot > 0 && $soma !== 100) {
                                                $diff = 100 - $soma;
                                                if ($qa >= $qb && $qa >= $qc)      $pa += $diff;
                                                elseif ($qb >= $qa && $qb >= $qc)  $pb += $diff;
                                                else                                $pc += $diff;
                                            }
                                        @endphp
                                        <div class="criterio-row">
                                            <div class="criterio-label">{{ $criterio }}</div>
                                            <div class="criterio-bar-wrap">
                                                <table class="bar-table">
                                                    <tr>
                                                        @if($pa > 0)<td class="bar-cell-a" style="width:{{ $pa }}%;"></td>@endif
                                                        @if($pb > 0)<td class="bar-cell-b" style="width:{{ $pb }}%;"></td>@endif
                                                        @if($pc > 0)<td class="bar-cell-c" style="width:{{ $pc }}%;"></td>@endif
                                                        @if($pa == 0 && $pb == 0 && $pc == 0)<td class="bar-cell-vazio" style="width:100%;"></td>@endif
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="criterio-resumo">
                                                @if($tot > 0)
                                                    <span class="badge-bom">{{ $qa }}A</span>
                                                    <span class="badge-medio">{{ $qb }}B</span>
                                                    <span class="badge-ruim">{{ $qc }}C</span>
                                                @else
                                                    <span class="sem-obs">Sem dados</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Alerta de perfil — sem emojis --}}
                                <div class="perfil-alerta alerta-{{ $nivelAlerta }}">
                                    @if($nivelAlerta === 'verde')
                                        <strong>[OK] Bom desempenho comportamental.</strong>
                                        {{ $pctA }}% dos conceitos são A{{ $criterioMaisForte ? " — destaque em {$criterioMaisForte}" : '' }}.
                                        Continue acompanhando a evolução do estudante.
                                    @elseif($nivelAlerta === 'amarelo')
                                        <strong>[ATENÇÃO] Atenção recomendada.</strong>
                                        {{ $pctC }}% dos conceitos são C.
                                        @if($criterioMaisFragil)
                                            O critério <strong>{{ $criterioMaisFragil }}</strong> é o que mais demanda atenção ({{ $maxC }} conceito(s) C).
                                        @endif
                                        Acompanhe a evolução e converse com o estudante.
                                    @else
                                        <strong>[CRÍTICO] Situação crítica.</strong>
                                        {{ $pctC }}% dos conceitos são C, indicando dificuldade comportamental acentuada.
                                        @if($criterioMaisFragil)
                                            O critério <strong>{{ $criterioMaisFragil }}</strong> é o mais crítico ({{ $maxC }} conceito(s) C).
                                        @endif
                                        Intervenção pedagógica prioritária é recomendada.
                                    @endif
                                </div>

                            </div>{{-- /perfil-body --}}
                        </div>{{-- /perfil-wrapper --}}
                        @endif

                        {{-- Avaliação geral da turma por área --}}
                        @php
                            $avalTurmaA1    = $conselho->avaliacao_turma_a1 ?? ($conselho->avaliacao_a1 ?? null);
                            $avalTurmaA2    = $conselho->avaliacao_turma_a2 ?? ($conselho->avaliacao_a2 ?? null);
                            $avalTurmaA3    = $conselho->avaliacao_turma_a3 ?? ($conselho->avaliacao_a3 ?? null);
                            $avalTurmaA4    = $conselho->avaliacao_turma_a4 ?? ($conselho->avaliacao_a4 ?? null);
                            $avalTurmaGeral = $conselho->avaliacao_turma_geral ?? ($conselho->avaliacao_geral ?? null);
                            $hasAvalTurma   = $avalTurmaA1 || $avalTurmaA2 || $avalTurmaA3 || $avalTurmaA4 || $avalTurmaGeral;
                        @endphp
                        @if($hasAvalTurma)
                            <div class="avaliacao-turma-wrapper">
                                <div class="avaliacao-turma-titulo">Avaliação Geral da Turma</div>
                                <div class="avaliacao-turma-body">
                                    <div class="avaliacao-turma-areas">
                                        <div class="avaliacao-turma-area-cell">
                                            <span class="avaliacao-turma-area-label">A1 — Técnica</span>
                                            <span class="avaliacao-turma-area-texto">{{ $avalTurmaA1 ?? '—' }}</span>
                                        </div>
                                        <div class="avaliacao-turma-area-cell">
                                            <span class="avaliacao-turma-area-label">A2 — Natureza</span>
                                            <span class="avaliacao-turma-area-texto">{{ $avalTurmaA2 ?? '—' }}</span>
                                        </div>
                                        <div class="avaliacao-turma-area-cell">
                                            <span class="avaliacao-turma-area-label">A3 — Humanas</span>
                                            <span class="avaliacao-turma-area-texto">{{ $avalTurmaA3 ?? '—' }}</span>
                                        </div>
                                        <div class="avaliacao-turma-area-cell">
                                            <span class="avaliacao-turma-area-label">A4 — Linguagens</span>
                                            <span class="avaliacao-turma-area-texto">{{ $avalTurmaA4 ?? '—' }}</span>
                                        </div>
                                    </div>
                                    @if($avalTurmaGeral)
                                        <div class="avaliacao-turma-geral">
                                            <span class="avaliacao-turma-geral-label">Avaliação Geral:</span>
                                            <span class="avaliacao-turma-geral-texto">{{ $avalTurmaGeral }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    @endif

                    </div>{{-- /conselho-block --}}

                @endforeach
            @else
                <div class="summary" style="margin:8px;">Nenhum conselho encontrado para este estudante.</div>
            @endif

            {{-- Acompanhamentos --}}
            @php
                $acompanhamentos = Acompanhamento::where('discente_id', $discente->id)
                    ->with(['turma','user'])
                    ->orderBy('data_hora','desc')
                    ->get();
            @endphp

            <div class="estudantes-header" style="margin: 14px 10px 0 10px; border-radius:4px 4px 0 0;">
                <span class="estudantes-title">Acompanhamentos</span>
                <span style="font-size:8px; color:#7f8c8d;">Total: {{ $acompanhamentos->count() }}</span>
            </div>

            @if($acompanhamentos->count() > 0)
                <table class="table-acompanhamentos" style="margin: 0 10px 14px 10px; width: calc(100% - 20px);">
                    <thead>
                        <tr>
                            <th style="width:5%">ID</th>
                            <th style="width:18%">Turma</th>
                            <th style="width:16%">Tipo</th>
                            <th style="width:33%">Responsável</th>
                            <th style="width:18%">Data / Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($acompanhamentos as $a)
                            <tr class="linha-dados">
                                <td class="text-center" style="font-weight:bold">{{ $a->id }}</td>
                                <td>{{ $a->turma->nome ?? '—' }}</td>
                                <td>{{ $a->tipo ?? '—' }}</td>
                                <td>{{ $a->user->nome ?? $a->user->name ?? '—' }}</td>
                                <td>{{ optional($a->data_hora)->format('d/m/Y H:i') ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="observacao-cell">
                                    <strong>Observação:</strong>
                                    @if(!empty($a->observacao))
                                        {{ strip_tags($a->observacao) }}
                                    @else
                                        <span class="sem-obs">Nenhuma observação registrada.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="summary" style="margin: 0 10px 14px 10px;">Nenhum acompanhamento registrado para este estudante.</div>
            @endif

        </div>{{-- /discente-wrapper --}}

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif

        @endforeach
    @else
        <div class="summary">
            <strong>Sem registros</strong>
            <p>Nenhum estudante encontrado para os filtros selecionados.</p>
        </div>
    @endif

</body>
</html>