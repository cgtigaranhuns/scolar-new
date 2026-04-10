<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Turma extends Model
{
    

    protected $fillable = [
        'nome',
        'codigo',
        'professor_id',
    ];

    public function discentes()
    {
        return $this->hasMany(Discente::class, 'turma', 'codigo');
    }

    public function discentesConselho()
    {
        return $this->hasMany(DiscentesConselho::class);
    }

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'professor_turma');
    }

    
}
