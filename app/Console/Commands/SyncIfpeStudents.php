<?php

namespace App\Console\Commands;

use App\Models\Discente;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncIfpeStudents extends Command
{
    protected $signature = 'ifpe:sync-students {--size=50} {--limit=} {--test}';
    protected $description = 'Sincroniza discentes e turmas (Curso + Período + Turno)';
    
    protected $apiBaseUrl = 'https://api.ifpe.edu.br/qacademico/';
    protected $apiToken = 'faBj4kkwVoJLsAnZOfAbwFvflyL5omG5';

    public function handle()
    {
        if ($this->option('test')) return $this->testConnection();

        $size = (int) $this->option('size');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info("📆 Início: " . Carbon::now()->toDateTimeString());
        $this->syncAllPages($size, $limit);
        $this->info("\n✅ Sincronização concluída!");
        return 0;
    }

    protected function syncAllPages($size, $limit)
    {
        $page = 0; $processed = 0;

        do {
            $currentSize = ($limit && ($processed + $size) > $limit) ? ($limit - $processed) : $size;
            $response = $this->makeApiRequest('students', ['page' => $page, 'size' => $currentSize]);
            
            if (!$response || !isset($response->json()['content'])) break;
            $students = $response->json()['content'];

            foreach ($students as $student) {
                if (trim($student['enrollmentStatus'] ?? '') !== 'Matriculado') continue;

                try {
                    $codigoTurma = $this->syncTurma($student);
                    $this->processStudent($student, $codigoTurma);
                    $processed++;
                    if ($limit && $processed >= $limit) break 2;
                } catch (\Exception $e) {
                    Log::error("Erro no estudante", ['matricula' => $student['enrollment'] ?? 'N/A', 'msg' => $e->getMessage()]);
                }
            }
            $page++;
        } while (!($response->json()['last'] ?? true));
    }

    protected function syncTurma(array $studentData)
    {
        $courseId = $studentData['courseId'] ?? '0';
        $periodo = $studentData['currentPeriod'] ?? '1';
        $shift = $studentData['shift'] ?? 'U';
        
        // Chave Única: ID do Curso + Período + Turno
        $codigoUnico = "{$courseId}-{$periodo}-{$shift}";

        $nomeTurno = match($shift) {
            'N' => 'NOITE', 'M' => 'MANHÃ', 'V' => 'TARDE', default => 'GERAL',
        };

        $nomeTurma = "{$periodo}º " . (explode(' - ', $studentData['courseName'] ?? '')[0]) . " - {$nomeTurno}";

        Turma::updateOrCreate(['codigo' => $codigoUnico], ['nome' => $nomeTurma]);

        return $codigoUnico;
    }

    protected function processStudent(array $studentData, string $codigoTurma)
    {
        Discente::updateOrCreate(
            ['matricula' => $studentData['enrollment']],
            [
                'nome' => $studentData['fullName'] ?? null,
                'email_discente' => $studentData['email'] ?? null,
                'data_nascimento' => isset($studentData['birthday']) ? Carbon::parse($studentData['birthday'])->format('Y-m-d') : null,
                'status_qa' => 'Matriculado',
                'turma' => $codigoTurma, // Referência para a turma específica (Período + Turno)
            ]
        );
    }

    protected function makeApiRequest($endpoint, $params)
    {
        return Http::withOptions(['verify' => false])
            ->withHeaders(['Authorization' => $this->apiToken, 'Accept' => 'application/json'])
            ->get($this->apiBaseUrl . $endpoint, $params);
    }
}