<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acompanhamento extends Model
{
    protected $fillable = [
        'turma_id',
        'discente_id',
        'user_id',
        'tipo',
        'data_hora',
        'observacao',
        
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function discente()
    {
        return $this->belongsTo(Discente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
