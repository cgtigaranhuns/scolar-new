<?php

namespace App\Http\Controllers;

use App\Models\Acompanhamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AcompanhamentoController extends Controller
{
    public function relatorio(Request $request)
    {
        $request->validate([
            'acompanhamento_id' => 'nullable|exists:acompanhamentos,id',
            'turma_id' => 'nullable|exists:turmas,id',
            'discente_id' => 'nullable|exists:discentes,id',
            'tipo' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date',
        ]);

        $acompanhamentoQuery = Acompanhamento::query()->with([
            'turma',
            'discente',
            'user',
        ]);

        if ($request->filled('acompanhamento_id')) {
            $acompanhamentoQuery->where('id', $request->acompanhamento_id);
        }

        if ($request->filled('turma_id')) {
            $acompanhamentoQuery->where('turma_id', $request->turma_id);
        }

        if ($request->filled('discente_id')) {
            $acompanhamentoQuery->where('discente_id', $request->discente_id);
        }

        if ($request->filled('tipo')) {
            $acompanhamentoQuery->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio')) {
            $acompanhamentoQuery->whereDate('data_hora', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $acompanhamentoQuery->whereDate('data_hora', '<=', $request->data_fim);
        }

        $dados = $acompanhamentoQuery->orderBy('discente_id', 'asc')->orderBy('data_hora', 'asc')->get();

        $nomeTurma = ($request->filled('turma_id') && $dados->isNotEmpty()) ? $dados->first()->turma->nome : 'todos';
        $dataInicio = $request->filled('data_inicio') ? $request->data_inicio : null;
        $dataFim = $request->filled('data_fim') ? $request->data_fim : null;

        $pdf = Pdf::loadView('pdfs.acompanhamento_relatorio', [
            'acompanhamentos' => $dados,
            'nomeTurma' => $nomeTurma,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,
            'tipoSelecionado' => $request->tipo,
            'discenteSelecionado' => $request->discente_id,
        ])->setPaper('a4', 'landscape');
        
        return $pdf->stream("relatorio-acompanhamentos.pdf");
    }
}