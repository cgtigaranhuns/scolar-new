<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log; // 👈 Importação necessária para usar os Logs

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 1. Agendamento dos conselhos - status às 01:00
        $schedule->command('conselho:atualizar-status')
            ->dailyAt('01:00')
            ->before(function () {
                Log::info('[Scheduler] Iniciando execução automática do comando: conselho:atualizar-status');
            })
            ->after(function () {
                Log::info('[Scheduler] Finalizada a execução do comando: conselho:atualizar-status');
            });

        // 2. Agendamento dos estudantes do IFPE às 01:15
        $schedule->command('ifpe:sync-students')
            ->dailyAt('01:15')
            ->withoutOverlapping()
            ->before(function () {
                Log::info('[Scheduler] Iniciando execução automática do comando: ifpe:sync-students');
            })
            ->after(function () {
                Log::info('[Scheduler] Finalizada a execução do comando: ifpe:sync-students');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}