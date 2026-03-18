<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'matricula',
        'area_conhecimento_id',
    ];

    public function areaConhecimento()
    {
        return $this->belongsTo(AreaConhecimento::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'professor_turma');
    }
}
