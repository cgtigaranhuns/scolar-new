<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaConhecimento extends Model
{
    protected $fillable = [
        'nome',
    ];

    public function professors()
    {
        return $this->hasMany(Professor::class);
    }
}
