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
        Schema::table('conselhos', function (Blueprint $table) {
            $table->text('avaliacao_01')->nullable()->after('professor04_id');
            $table->text('avaliacao_02')->nullable()->after('avaliacao_01');
            $table->text('avaliacao_03')->nullable()->after('avaliacao_02');
            $table->text('avaliacao_04')->nullable()->after('avaliacao_03');
            $table->text('avaliacao_geral')->nullable()->after('avaliacao_04');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conselhos', function (Blueprint $table) {
            $table->dropColumn([
                'avaliacao_01',
                'avaliacao_02',
                'avaliacao_03',
                'avaliacao_04',
                'avaliacao_geral',
            ]);
        });
    }
};