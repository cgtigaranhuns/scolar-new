<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TurmaRelatorioController extends Controller
{
    public function relatorioTurmas(Request $request)
    {
        $request->validate([
            'turma_id' => 'nullable|exists:turmas,id',
            'conselho_id' => 'nullable|exists:conselhos,id',
            
        ]);

        $turmaQuery = \App\Models\Turma::query()->with([
            'conselhos' => function ($query) use ($request) {
                if ($request->filled('conselho_id')) {
                    $query->where('id', $request->conselho_id);
                }
            },
            'discentes',
            'professores'
        ]);

        if($request->filled('turma_id')) $turmaQuery->where('id', $request->turma_id);
        if($request->filled('conselho_id')) $turmaQuery->whereHas('conselhos', fn($q) => $q->where('id', $request->conselho_id));

        $dados = $turmaQuery->get();

        $nomeTurma = ($request->filled('turma_id') && $dados->isNotEmpty()) ? $dados->first()->nome : 'todos';
        $nomeConselho = $request->filled('conselho_id')
            ? (\App\Models\Conselho::find($request->conselho_id)?->descricao ?? 'todos')
            : 'todos';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.turma_relatorio', [
            'turmas' => $dados, 
            'nomeTurma' => $nomeTurma, 
            'nomeConselho' => $nomeConselho
        ])->setPaper('a4', 'landscape');
        return $pdf->stream("relatorio-turmas.pdf");    
       


    }
}