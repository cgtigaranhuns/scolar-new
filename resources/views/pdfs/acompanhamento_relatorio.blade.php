<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Acompanhamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            box-sizing: border-box;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
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

        .summary {
            background-color: #ecf0f1;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 11px;
        }

        .summary strong {
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        /* Linhas alternadas — não afeta linhas de grupo nem observação */
        .table-acompanhamentos tbody tr.linha-dados:nth-child(even) td {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .status-concluido,
        .status-aprovado,
        .status-finalizado,
        .status-liberado {
            color: #27ae60;
            font-weight: bold;
        }

        .status-aberto,
        .status-em-andamento,
        .status-agendado {
            color: #2980b9;
            font-weight: bold;
        }

        .status-pendente,
        .status-atencao,
        .status-outros {
            color: #f39c12;
            font-weight: bold;
        }

        .status-reprovado,
        .status-retido {
            color: #e74c3c;
            font-weight: bold;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
        }

        .badge-tipo {
            background-color: #34495e;
        }

        .observacao-cell {
            font-size: 9px;
            color: #555;
            font-style: italic;
        }

        .sem-obs {
            color: #aaa;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            margin: 15px 0 6px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        .ranking-table th,
        .ranking-table td {
            font-size: 10px;
        }

        .ranking-table tbody tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        .grupo-discente {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 11px;
            padding: 6px 8px;
        }

        .grupo-discente span {
            font-weight: normal;
            font-size: 10px;
            color: #bdc3c7;
            margin-left: 8px;
        }
    </style>
</head>

<body>
    @php use Illuminate\Support\Str; @endphp

    <div class="header">
        @include('layouts.cabecalho')
        <h1>Relatório de Acompanhamentos</h1>
        <p>Turma: {{ $nomeTurma }} | Tipo: {{ $tipoSelecionado ?? 'Todos' }} | Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Período: {{ $dataInicio ?? 'início' }} – {{ $dataFim ?? 'fim' }}</p>
    </div>

    @if ($acompanhamentos->count() > 0)
        @php
            $tipoContagens   = $acompanhamentos->groupBy(fn($item) => $item->tipo   ?? 'Sem tipo')->map->count();
            $discenteContagens = $acompanhamentos
                ->groupBy(fn($item) => $item->discente->nome ?? 'Sem nome')
                ->map->count()
                ->sortDesc();
            $topDiscentes = $discenteContagens->take(10);
        @endphp

        <div class="summary">
            <strong>Resumo geral:</strong>
            Total de acompanhamentos: {{ $acompanhamentos->count() }} |
            Estudantes distintos: {{ $discenteContagens->count() }} |
            Tipos registrados: {{ $tipoContagens->count() }}
        </div>

        {{-- Tabela agrupada por estudante --}}
        @php
            $porDiscente = $acompanhamentos->groupBy(fn($a) => $a->discente->id ?? 0);
        @endphp

        <table class="table-acompanhamentos">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 18%;">Turma</th>
                    <th style="width: 13%;">Tipo</th>
                    <th style="width: 18%;">Responsável</th>
                    <th style="width: 15%;">Data / Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($porDiscente as $discenteId => $registros)
                    @php $primeiro = $registros->first(); @endphp
                    <tr>
                        <td colspan="5" class="grupo-discente">
                            {{ $primeiro->discente->nome ?? '—' }}
                            <span>Matrícula: {{ $primeiro->discente->matricula ?? '—' }}</span>
                            <span>| {{ $registros->count() }} acompanhamento(s)</span>
                        </td>
                    </tr>
                    @foreach ($registros as $acompanhamento)
                        <tr class="linha-dados">
                            <td class="text-center" style="font-weight: bold;">{{ $acompanhamento->id }}</td>
                            <td>{{ $acompanhamento->turma->nome ?? '—' }}</td>
                            <td>{{ $acompanhamento->tipo ?? '—' }}</td>
                            <td>{{ $acompanhamento->user->nome ?? $acompanhamento->user->name ?? '—' }}</td>
                            <td>{{ optional($acompanhamento->data_hora)->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                        <tr class="linha-obs">
                            <td colspan="5" class="observacao-cell">
                                <strong>Observação:</strong>
                                @if (!empty($acompanhamento->observacao))
                                    {{ strip_tags($acompanhamento->observacao) }}
                                @else
                                    <span class="sem-obs">Nenhuma observação registrada.</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        @if ($topDiscentes->count() > 0)
            <div class="section-title">Top {{ $topDiscentes->count() }} estudantes com mais acompanhamentos</div>
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="width: 8%;">Posição</th>
                        <th>Estudante</th>
                        <th style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topDiscentes as $nome => $count)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}º</td>
                            <td>{{ $nome }}</td>
                            <td class="text-center">{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @else
        <div class="summary">
            <strong>Sem registros</strong>
            <p>Nenhum acompanhamento encontrado para os filtros selecionados.</p>
        </div>
    @endif
</body>

</html>