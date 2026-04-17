<?php

namespace App\Http\Controllers;

use App\Models\Conselho;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ConselhoController extends Controller
{
    public function relatorio(Request $request)
    {
        $request->validate([
            'conselho_id' => 'nullable|exists:conselhos,id',
            'turma_id' => 'nullable|exists:turmas,id',
            'unidade' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $conselhoQuery = Conselho::query()->with([
            'turma', 
            'discentesConselho.discente',
            'professor01', 
            'professor02', 
            'professor03', 
            'professor04'
        ]);

        if($request->filled('conselho_id')) $conselhoQuery->where('id', $request->conselho_id);
        if($request->filled('turma_id')) $conselhoQuery->where('turma_id', $request->turma_id);
        if($request->filled('unidade')) $conselhoQuery->where('unidade', $request->unidade);
        if($request->filled('status')) $conselhoQuery->where('status', $request->status);

        $dados = $conselhoQuery->get();
        
        $nomeTurma = ($request->filled('turma_id') && $dados->isNotEmpty()) ? $dados->first()->turma->nome : 'todos';
        $nomeConselho = ($request->filled('conselho_id') && $dados->isNotEmpty()) ? ($dados->first()->descricao ?? $dados->first()->descrição) : 'todos';

        $pdf = Pdf::loadView('pdfs.conselho_relatorio', [
            'conselhos' => $dados, 
            'nomeTurma' => $nomeTurma, 
            'nomeConselho' => $nomeConselho
        ])->setPaper('a4', 'landscape');
        
        return $pdf->stream("relatorio-conselhos.pdf");
    }
}