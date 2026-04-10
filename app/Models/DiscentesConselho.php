<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscentesConselho extends Model
{
    

    protected $fillable = [
        'conselho_id',
        'discente_id',
        'nt_a1_participacao',
        'nt_a1_interesse',
        'nt_a1_organizacao',
        'nt_a1_comprometimento',
        'nt_a1_disciplina',
        'nt_a1_cooperacao',
        'obs_a1_gestao',
        'obs_a1_pais',
        'info_a1_complementares',
        'nt_a2_participacao',
        'nt_a2_interesse',
        'nt_a2_organizacao',
        'nt_a2_comprometimento',
        'nt_a2_disciplina',
        'nt_a2_cooperacao',
        'obs_a2_gestao',
        'obs_a2_pais',
        'info_a2_complementares',   
        'nt_a3_participacao',
        'nt_a3_interesse',
        'nt_a3_organizacao',
        'nt_a3_comprometimento',
        'nt_a3_disciplina',
        'nt_a3_cooperacao',
        'obs_a3_gestao',
        'obs_a3_pais',
        'info_a3_complementares',
        'nt_a4_participacao',
        'nt_a4_interesse',
        'nt_a4_organizacao',
        'nt_a4_comprometimento',
        'nt_a4_disciplina',
        'nt_a4_cooperacao',
        'obs_a4_gestao',
        'obs_a4_pais',
        'info_a4_complementares', 
        'status_geral_avaliacoes', 
        'status_avaliacao_a1', 
        'status_avaliacao_a2', 
        'status_avaliacao_a3', 
        'status_avaliacao_a4', 
        'data_avaliacao_a1', 
        'data_avaliacao_a2', 
        'data_avaliacao_a3', 
        'data_avaliacao_a4', 
    ];

    public function conselho()
    {
        return $this->belongsTo(Conselho::class);
    }

    public function discente()
    {
        return $this->belongsTo(Discente::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
    
}
