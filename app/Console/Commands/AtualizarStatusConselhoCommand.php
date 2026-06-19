<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conselho;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\LiberacaoConselhoEmail;

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

            // Enviar e-mail para os professores cadastrados no conselho
            // Somente para unidades 1 ou 3
            $unidade = (int) $conselho->unidade;
            if ($unidade === 1 || $unidade === 3) {
                $professores = collect([
                    $conselho->professor01,
                    $conselho->professor02,
                    $conselho->professor03,
                    $conselho->professor04,
                ])->filter();

                // Envia para cada professor com e-mail válido (não duplicando)
                $emails = $professores->pluck('email')->filter()->unique();

                $sentCount = 0;
                $failed = [];

                foreach ($emails as $email) {
                    try {
                        Mail::to($email)->send(new LiberacaoConselhoEmail($conselho));
                        $sentCount++;
                        Log::info('E-mail de liberação enviado', [
                            'conselho_id' => $conselho->id,
                            'email' => $email,
                            'descricao' => $conselho->descricao ?? null,
                        ]);
                    } catch (\Exception $e) {
                        $failed[] = ['email' => $email, 'error' => $e->getMessage()];
                        Log::error('Erro ao enviar e-mail de liberação', [
                            'conselho_id' => $conselho->id,
                            'email' => $email,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                // Log resumo por conselho
                Log::info('Resumo de envio de liberação para conselho', [
                    'conselho_id' => $conselho->id,
                    'enviados' => $sentCount,
                    'falhas' => count($failed),
                ]);

                // Opcional: exibir no console também
                if ($sentCount > 0) {
                    $this->info("E-mails enviados para conselho {$conselho->id}: {$sentCount}");
                }
                if (!empty($failed)) {
                    $this->error("Falhas ao enviar e-mails para conselho {$conselho->id}: " . count($failed));
                }
            }
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