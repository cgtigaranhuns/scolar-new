<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conselho;
use Carbon\Carbon;

class AtualizarStatusConselhoCommand extends Command
{
    /**
     * O nome e a assinatura do comando no console.
     *
     * @var string
     */
    protected $signature = 'conselho:atualizar-status';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Altera o status dos conselhos para Liberado na data inicial e Concluído após a data fim.';

    /**
     * Executa o comando do console.
     */
    public function handle()
    {
        // Usamos o Carbon::today() pois seus campos são DatePicker (apenas Y-m-d)
        $hoje = Carbon::today();

        // 1. LIBERAR CONSELHOS
        // Busca conselhos 'Agendados' que já chegaram na data_inicio e ainda estão dentro do prazo
        $conselhosParaLiberar = Conselho::where('status', 'Agendado')
            ->where('data_inicio', '<=', $hoje)
            ->where('data_fim', '>=', $hoje)
            ->get();

        foreach ($conselhosParaLiberar as $conselho) {
            $conselho->update(['status' => 'Liberado']);
        }

        // 2. CONCLUIR/BLOQUEAR CONSELHOS EXPIRADOS
        // Busca conselhos que estão como 'Liberado' (ou 'Agendado' que expirou sem liberar) 
        // e que a data_fim já passou. Ignora 'Cancelado'.
        $conselhosParaConcluir = Conselho::whereIn('status', ['Agendado', 'Liberado'])
            ->where('data_fim', '<', $hoje)
            ->get();

        foreach ($conselhosParaConcluir as $conselho) {
            $conselho->update(['status' => 'Concluído']);
        }

        // Feedback no console/log do Laravel
        $this->info("Rotina executada com sucesso!");
        $this->info("- " . $conselhosParaLiberar->count() . " conselho(s) alterado(s) para 'Liberado'.");
        $this->info("- " . $conselhosParaConcluir->count() . " conselho(s) alterado(s) para 'Concluído'.");

        return Command::SUCCESS;
    }
}