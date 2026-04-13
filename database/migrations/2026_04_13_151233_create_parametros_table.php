<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('campus');
            $table->string('endereco');
            $table->string('telefone');
            $table->string('setor_ensino');
            $table->string('sigla_setor_ensino');
            $table->string('versao_sistema');
            $table->string('versao_banco_dados');   
            $table->string('data_atualizacao_sistema'); 
            $table->string('data_atualizacao_banco_dados');
            $table->string('tipo_envio_email');
            $table->string('servidor_email');
            $table->string('porta_email');
            $table->string('email_seguro');
            $table->string('usuario_email');
            $table->string('senha_email');
            $table->string('email_copia');
            $table->string('email_administratador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros');
    }
};
