<?php

namespace App\Http\Controllers;

use App\Models\Discente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioGeralDiscenteController extends Controller
{
    public function relatorioGeralDiscente(Request $request)
    {
        $request->validate([
            'discente_id' => 'nullable|exists:discentes,id',            
            'data_inicio' => 'nullable|date',
            'data_fim'    => 'nullable|date',
        ]);

        $query = Discente::query()->with([
            'turmaRelacionada',
            // Carrega os registros do conselho e, dentro deles, o próprio conselho
            // (com turma e professores) para evitar N+1 no blade.
            'discentesConselhos.conselho.turma',
            'discentesConselhos.conselho.professor01',
            'discentesConselhos.conselho.professor02',
            'discentesConselhos.conselho.professor03',
            'discentesConselhos.conselho.professor04',
        ]);

        if ($request->filled('discente_id')) {
            $query->where('id', $request->discente_id);
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_inicio', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_fim', '<=', $request->data_fim);
        }

        $pdf = Pdf::loadView('pdfs.relatorio_geral_discente', [
            'discentes' => $query->get(),
        ]);

        return $pdf->stream('relatorio_geral_discente.pdf');
    }
}