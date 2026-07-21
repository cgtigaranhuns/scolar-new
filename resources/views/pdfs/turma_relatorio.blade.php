<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #222;
        }

        h1 {
            font-size: 16px;
            margin: -8px 0 4px 0;
            text-align: center;
        }

        .subtitulo {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 14px;
        }

        .turma-bloco {
            margin-bottom: 22px;
        }

        .turma-titulo {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: bold;
        }

        .conselho-bloco {
            border: 1px solid #cccccc;
            padding: 10px;
            margin-top: 8px;
        }

        .conselho-titulo {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .conselho-periodo {
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
        }

        table.conceitos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.conceitos th,
        table.conceitos td {
            border: 1px solid #dddddd;
            padding: 4px 6px;
            text-align: center;
        }

        table.conceitos th {
            background-color: #ecf0f1;
            font-size: 10px;
        }

        table.conceitos td.area-nome {
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        table.conceitos tr.linha-total td {
            font-weight: bold;
            background-color: #f7f9f9;
        }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-weight: bold;
            color: #ffffff;
        }

        .badge-a { background-color: #27ae60; }
        .badge-b { background-color: #f39c12; }
        .badge-c { background-color: #c0392b; }

        .comentarios-titulo {
            font-size: 11px;
            font-weight: bold;
            margin: 10px 0 4px 0;
            border-top: 1px solid #dddddd;
            padding-top: 6px;
        }

        table.comentarios {
            width: 100%;
            border-collapse: collapse;
        }

        table.comentarios td {
            border: 1px solid #eeeeee;
            padding: 5px 7px;
            vertical-align: top;
        }

        table.comentarios td.area-label {
            width: 22%;
            font-weight: bold;
            font-size: 10px;
            background-color: #fafafa;
        }

        table.comentarios td.professor-label {
            font-size: 9px;
            color: #666;
            display: block;
            margin-top: 2px;
            font-weight: normal;
        }

        .sem-dados {
            text-align: center;
            color: #888;
            padding: 20px;
        }

        .graficos-titulo {
            font-size: 11px;
            font-weight: bold;
            margin: 4px 0 6px 0;
        }

        .grafico-bloco {
            margin-bottom: 12px;
            text-align: center;
        }

        .grafico-subtitulo {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 6px;
        }

        table.bar-chart-table {
            width: 100%;
        }

        table.bar-chart-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 6px;
        }

        .bar-track {
            position: relative;
            height: 110px;
            width: 44px;
            margin: 0 auto;
            background-color: #f4f4f4;
            border: 1px solid #e0e0e0;
        }

        .bar-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .bar-valor {
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
        }

        .bar-label {
            font-size: 9px;
            color: #666;
        }

        table.barra-percentual {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            border-collapse: collapse;
            height: 34px;
        }

        table.barra-percentual td {
            height: 34px;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .segmento-a { background-color: #27ae60; }
        .segmento-b { background-color: #f39c12; }
        .segmento-c { background-color: #c0392b; }

        table.legenda-pizza {
            width: 100%;
            max-width: 420px;
            margin: 6px auto 0 auto;
        }

        table.legenda-pizza td {
            text-align: center;
            font-size: 9px;
            padding: 2px;
        }
    </style>
</head>
<body>

      <!-- Cabeçalho -->
    <div class="header">
        @include('layouts.cabecalho')       
        
    </div>

    <h1>Relatório de Conceitos por Turma</h1>
    

    @php
        $areasMap = [
            'a1' => ['label' => 'Área Técnica', 'professor' => 'professor01', 'avaliacao' => 'avaliacao_a1'],
            'a2' => ['label' => 'Ciências da Natureza, Matemática e suas Tecnologias', 'professor' => 'professor02', 'avaliacao' => 'avaliacao_a2'],
            'a3' => ['label' => 'Ciências Humanas e suas Tecnologias', 'professor' => 'professor03', 'avaliacao' => 'avaliacao_a3'],
            'a4' => ['label' => 'Linguagens, Códigos e suas Tecnologias', 'professor' => 'professor04', 'avaliacao' => 'avaliacao_a4'],
        ];

        $criterios = ['participacao', 'interesse', 'organizacao', 'comprometimento', 'disciplina', 'cooperacao'];
    @endphp

    @forelse($turmas as $turma)
        <div class="turma-bloco">
            <div class="turma-titulo">Turma: {{ $turma->nome }}</div>

            @forelse($turma->conselhos->sortBy('data_inicio') as $conselho)
                @php
                    // Contagem de conceitos (A/B/C) por área, a partir dos critérios
                    // lançados para cada discente neste conselho.
                    $tallyGeral = ['A' => 0, 'B' => 0, 'C' => 0, 'total' => 0];
                    $tallyPorArea = [];

                    foreach ($areasMap as $areaKey => $areaInfo) {
                        $tallyPorArea[$areaKey] = ['A' => 0, 'B' => 0, 'C' => 0, 'total' => 0];

                        foreach ($conselho->discentesConselho as $dc) {
                            foreach ($criterios as $criterio) {
                                $campo = "nt_{$areaKey}_{$criterio}";
                                $valor = strtoupper(trim((string) ($dc->{$campo} ?? '')));

                                if (in_array($valor, ['A', 'B', 'C'], true)) {
                                    $tallyPorArea[$areaKey][$valor]++;
                                    $tallyPorArea[$areaKey]['total']++;
                                    $tallyGeral[$valor]++;
                                    $tallyGeral['total']++;
                                }
                            }
                        }
                    }

                    $pct = function ($qtd, $total) {
                        return $total > 0 ? number_format(($qtd / $total) * 100, 1) . '%' : '-';
                    };

                    // Gráfico de colunas (quantidades) — altura das barras em px
                    $alturaMaxBarra = 100;
                    $maiorValor = max($tallyGeral['A'], $tallyGeral['B'], $tallyGeral['C'], 1);
                    $alturasBarra = [];
                    foreach (['A', 'B', 'C'] as $k) {
                        $valor = $tallyGeral[$k];
                        $alturasBarra[$k] = $valor > 0 ? max(2, round(($valor / $maiorValor) * $alturaMaxBarra)) : 0;
                    }

                    // Percentual por conceito — barra horizontal empilhada (sem SVG),
                    // mesma técnica de div/td com largura em % já usada no gráfico de colunas.
                    $percentuais = [];
                    if ($tallyGeral['total'] > 0) {
                        foreach (['A', 'B', 'C'] as $k) {
                            $percentuais[$k] = ($tallyGeral[$k] / $tallyGeral['total']) * 100;
                        }
                    }
                @endphp

                <div class="conselho-bloco">
                    <div class="conselho-titulo">{{ $conselho->descricao }} ({{ $conselho->unidade }})</div>
                    <div class="conselho-periodo">
                        Período:
                        {{ \Carbon\Carbon::parse($conselho->data_inicio)->format('d/m/Y') }}
                        a
                        {{ \Carbon\Carbon::parse($conselho->data_fim)->format('d/m/Y') }}
                    </div>

                    <table class="conceitos">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Área</th>
                                <th>Qtd. A</th>
                                <th>% A</th>
                                <th>Qtd. B</th>
                                <th>% B</th>
                                <th>Qtd. C</th>
                                <th>% C</th>
                                <th>Total avaliações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($areasMap as $areaKey => $areaInfo)
                                @php $t = $tallyPorArea[$areaKey]; @endphp
                                <tr>
                                    <td class="area-nome">{{ $areaInfo['label'] }}</td>
                                    <td><span class="badge badge-a">{{ $t['A'] }}</span></td>
                                    <td>{{ $pct($t['A'], $t['total']) }}</td>
                                    <td><span class="badge badge-b">{{ $t['B'] }}</span></td>
                                    <td>{{ $pct($t['B'], $t['total']) }}</td>
                                    <td><span class="badge badge-c">{{ $t['C'] }}</span></td>
                                    <td>{{ $pct($t['C'], $t['total']) }}</td>
                                    <td>{{ $t['total'] }}</td>
                                </tr>
                            @endforeach
                            <tr class="linha-total">
                                <td class="area-nome">Total Geral</td>
                                <td>{{ $tallyGeral['A'] }}</td>
                                <td>{{ $pct($tallyGeral['A'], $tallyGeral['total']) }}</td>
                                <td>{{ $tallyGeral['B'] }}</td>
                                <td>{{ $pct($tallyGeral['B'], $tallyGeral['total']) }}</td>
                                <td>{{ $tallyGeral['C'] }}</td>
                                <td>{{ $pct($tallyGeral['C'], $tallyGeral['total']) }}</td>
                                <td>{{ $tallyGeral['total'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="graficos-titulo">Conceitos — Total Geral</div>

                    <div class="grafico-bloco">
                        <div class="grafico-subtitulo">Quantidade por conceito</div>
                        <table class="bar-chart-table">
                            <tr>
                                @foreach(['A', 'B', 'C'] as $k)
                                    <td>
                                        <div class="bar-track">
                                            <div class="bar-fill badge-{{ strtolower($k) }}" style="height: {{ $alturasBarra[$k] }}px;"></div>
                                        </div>
                                        <div class="bar-valor">{{ $tallyGeral[$k] }}</div>
                                        <div class="bar-label">Conceito {{ $k }}</div>
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    </div>

                    <div class="grafico-bloco">
                        <div class="grafico-subtitulo">Percentual por conceito</div>
                        @if($tallyGeral['total'] > 0)
                            <table class="barra-percentual">
                                <tr>
                                    @foreach(['A', 'B', 'C'] as $k)
                                        @if($percentuais[$k] > 0)
                                            <td class="segmento-{{ strtolower($k) }}" style="width: {{ number_format($percentuais[$k], 2) }}%;">
                                                @if($percentuais[$k] >= 8)
                                                    {{ number_format($percentuais[$k], 1) }}%
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            </table>
                            <table class="legenda-pizza">
                                <tr>
                                    @foreach(['A', 'B', 'C'] as $k)
                                        <td>
                                            <span class="badge badge-{{ strtolower($k) }}">{{ $k }}</span>
                                            {{ $pct($tallyGeral[$k], $tallyGeral['total']) }}
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        @else
                            <div class="sem-dados">Sem dados para exibir.</div>
                        @endif
                    </div>

                    @php
                        $temComentarios = collect($areasMap)->contains(fn($info) => trim((string) $conselho->{$info['avaliacao']}) !== '');
                    @endphp
 
                    @if($temComentarios)
                        <div class="comentarios-titulo">Comentários por área sobre a turma</div>
                        <table class="comentarios">
                            @foreach($areasMap as $areaKey => $areaInfo)
                                @php
                                    $comentario = trim((string) $conselho->{$areaInfo['avaliacao']});
                                @endphp
                                @if($comentario !== '')
                                    <tr>
                                        <td class="area-label">{{ $areaInfo['label'] }}</td>
                                        <td>{{ $comentario }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    @endif
                </div>
            @empty
                <div class="sem-dados">Nenhum conselho encontrado para esta turma.</div>
            @endforelse
        </div>
    @empty
        <div class="sem-dados">Nenhuma turma encontrada para os filtros selecionados.</div>
    @endforelse
 
</body>
</html>