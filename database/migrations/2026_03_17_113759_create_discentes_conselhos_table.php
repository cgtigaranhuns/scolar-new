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
        Schema::create('discentes_conselhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conselho_id');
            $table->foreignId('discente_id');
            $table->string('nt_a1_participacao')->nullable();
            $table->string('nt_a1_interesse')->nullable();
            $table->string('nt_a1_organizacao')->nullable();
            $table->string('nt_a1_comprometimento')->nullable();
            $table->string('nt_a1_disciplina')->nullable();
            $table->string('nt_a1_cooperacao')->nullable();
            $table->longText('obs_a1_gestao')->nullable();
            $table->longText('obs_a1_pais')->nullable();
            $table->longText('info_a1_complementares')->nullable();
            $table->string('nt_a2_participacao')->nullable();
            $table->string('nt_a2_interesse')->nullable();
            $table->string('nt_a2_organizacao')->nullable();
            $table->string('nt_a2_comprometimento')->nullable();
            $table->string('nt_a2_disciplina')->nullable();
            $table->string('nt_a2_cooperacao')->nullable();
            $table->longText('obs_a2_gestao')->nullable();
            $table->longText('obs_a2_pais')->nullable();
            $table->longText('info_a2_complementares')->nullable();   
            $table->string('nt_a3_participacao')->nullable();
            $table->string('nt_a3_interesse')->nullable();
            $table->string('nt_a3_organizacao')->nullable();
            $table->string('nt_a3_comprometimento')->nullable();
            $table->string('nt_a3_disciplina')->nullable();
            $table->string('nt_a3_cooperacao')->nullable();
            $table->longText('obs_a3_gestao')->nullable();
            $table->longText('obs_a3_pais')->nullable();
            $table->longText('info_a3_complementares')->nullable();
            $table->string('nt_a4_participacao')->nullable();
            $table->string('nt_a4_interesse')->nullable();
            $table->string('nt_a4_organizacao')->nullable();
            $table->string('nt_a4_comprometimento')->nullable();
            $table->string('nt_a4_disciplina')->nullable();
            $table->string('nt_a4_cooperacao')->nullable();
            $table->longText('obs_a4_gestao')->nullable();
            $table->longText('obs_a4_pais')->nullable();
            $table->longText('info_a4_complementares')->nullable();
            $table->string('status_geral_avaliacoes')->nullable();
            $table->string('status_avaliacao_a1')->nullable();
            $table->string('status_avaliacao_a2')->nullable();
            $table->string('status_avaliacao_a3')->nullable();
            $table->string('status_avaliacao_a4')->nullable();
            $table->date('data_avaliacao_a1')->nullable();
            $table->date('data_avaliacao_a2')->nullable();
            $table->date('data_avaliacao_a3')->nullable();
            $table->date('data_avaliacao_a4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discentes_conselhos');
    }
};
