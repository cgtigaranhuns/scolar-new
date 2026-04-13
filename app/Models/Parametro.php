<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $fillable = [
        'campus',
        'endereco',
        'telefone',
        'setor_ensino',
        'sigla_setor_ensino',
        'versao_sistema',
        'versao_banco_dados',
        'data_atualizacao_sistema',
        'data_atualizacao_banco_dados',
        'tipo_envio_email',
        'servidor_email',
        'porta_email',
        'email_seguro',
        'usuario_email',
        'senha_email',
        'email_copia',
        'email_administratador'
    ];
}
