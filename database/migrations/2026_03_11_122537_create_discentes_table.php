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
        Schema::create('discentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email_discente')->unique();
            $table->string('email_responsavel')->nullable();
            $table->date('data_nascimento');
            $table->string('matricula')->unique();
            $table->string('turma');
            $table->string('status');
            $table->string('foto')->nullable();
            $table->string('informacoes_adicionais')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discentes');
    }
};
