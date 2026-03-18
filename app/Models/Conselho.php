<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conselho extends Model
{
    protected $fillable = [
        'descricao',
        'turma_id',
        'data_inicio',
        'data_fim',
        'unidade',
        'professor01_id',
        'professor02_id',
        'professor03_id',
        'professor04_id',
        'status',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function professor01()
    {
        return $this->belongsTo(Professor::class, 'professor01_id');
    }

    public function professor02()
    {
        return $this->belongsTo(Professor::class, 'professor02_id');
    }

    public function professor03()
    {
        return $this->belongsTo(Professor::class, 'professor03_id');
    }

    public function professor04()
    {
        return $this->belongsTo(Professor::class, 'professor04_id');
    }

    public function discentesConselho()
    {
        return $this->hasMany(DiscentesConselho::class);
    }
}
