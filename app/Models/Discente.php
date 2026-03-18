<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discente extends Model
{
    protected $fillable = [
        'nome',
        'email_discente',
        'email_responsavel',
        'data_nascimento',
        'matricula',
        'turma',
        'status_qa',
        'foto',
        'informacoes_adicionais',
    ];

    public function turmaRelacionada()
    {
        return $this->belongsTo(Turma::class, 'turma', 'codigo');
        
    }

    public function discentesConselhos()
    {
        return $this->hasMany(DiscentesConselho::class);
    }
}
