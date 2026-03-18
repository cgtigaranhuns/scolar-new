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
        Schema::create('conselhos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->foreignId('turma_id');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('unidade');
            $table->foreignId('professor01_id');
            $table->foreignId('professor02_id');
            $table->foreignId('professor03_id');
            $table->foreignId('professor04_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conselhos');
    }
};
