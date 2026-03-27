<?php

namespace App\Console\Commands;

use App\Models\Discente;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Comando Artisan responsável por sincronizar discentes e turmas
 * a partir da API do IFPE (Q-Acadêmico).
 *
 * Execução:
 *   php artisan ifpe:sync-students
 *   php artisan ifpe:sync-students --size=100        (define quantos alunos buscar por página)
 *   php artisan ifpe:sync-students --limit=200       (limita o total de alunos processados)
 *   php artisan ifpe:sync-students --test            (testa a conexão com a API)
 */
class SyncIfpeStudents extends Command
{
    /**
     * Assinatura do comando Artisan com suas opções:
     *  --size  : número de registros por página na API (padrão: 50)
     *  --limit : limite total de alunos a processar (opcional)
     *  --test  : executa apenas um teste de conectividade com a API
     */
    protected $signature = 'ifpe:sync-students {--size=50} {--limit=} {--test}';

    /** Descrição exibida ao rodar `php artisan list` */
    protected $description = 'Sincroniza discentes e turmas (Curso + Período + Turno)';

    /** URL base da API do Q-Acadêmico do IFPE */
    protected $apiBaseUrl = 'https://api.ifpe.edu.br/qacademico/';

    /** Token de autenticação para as requisições à API */
    protected $apiToken = 'faBj4kkwVoJLsAnZOfAbwFvflyL5omG5';


    /**
     * Ponto de entrada do comando.
     * Verifica se é um teste de conexão ou inicia a sincronização completa.
     */
    public function handle()
    {
        // Se a flag --test foi passada, executa apenas o teste de conexão e encerra
        if ($this->option('test')) return $this->testConnection();

        // Lê as opções de paginação e limite informadas pelo operador
        $size  = (int) $this->option('size');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info("📆 Início: " . Carbon::now()->toDateTimeString());

        // Inicia o processo de sincronização percorrendo todas as páginas da API
        $this->syncAllPages($size, $limit);

        $this->info("\n✅ Sincronização concluída!");
        return 0;
    }


    /**
     * Percorre todas as páginas da API de estudantes e processa cada registro.
     *
     * O loop continua até que:
     *   - a API indique que é a última página (campo `last` = true), ou
     *   - o número de alunos processados atinja o limite definido em --limit.
     *
     * @param int      $size  Quantidade de registros por página
     * @param int|null $limit Limite total de registros a processar (null = sem limite)
     */
    protected function syncAllPages($size, $limit)
    {
        $page      = 0; // Página atual da API (começa em 0)
        $processed = 0; // Contador de alunos processados com sucesso

        do {
            // Ajusta o tamanho da última página para não ultrapassar o limite definido
            $currentSize = ($limit && ($processed + $size) > $limit)
                ? ($limit - $processed)
                : $size;

            // Faz a requisição para o endpoint 'students' com paginação
            $response = $this->makeApiRequest('students', ['page' => $page, 'size' => $currentSize]);

            // Interrompe o loop se a resposta for inválida ou não contiver 'content'
            if (!$response || !isset($response->json()['content'])) break;

            $students = $response->json()['content']; // Array de alunos retornados pela página

            foreach ($students as $student) {
                // Ignora alunos que não estão com status "Matriculado"
                if (trim($student['enrollmentStatus'] ?? '') !== 'Matriculado') continue;

                try {
                    // Garante que a turma existe no banco e obtém seu código único
                    $codigoTurma = $this->syncTurma($student);

                    // Cria ou atualiza o registro do discente no banco de dados
                    $this->processStudent($student, $codigoTurma);

                    $processed++;

                    // Se atingiu o limite definido, encerra ambos os loops (foreach e do-while)
                    if ($limit && $processed >= $limit) break 2;

                } catch (\Exception $e) {
                    // Registra o erro no log sem interromper o processamento dos demais alunos
                    Log::error("Erro no estudante", [
                        'matricula' => $student['enrollment'] ?? 'N/A',
                        'msg'       => $e->getMessage(),
                    ]);
                }
            }

            $page++; // Avança para a próxima página

        // Continua enquanto a API indicar que não é a última página
        } while (!($response->json()['last'] ?? true));
    }

    /**
     * Cria ou atualiza uma Turma no banco de dados com base nos dados do aluno.
     *
     * A chave única da turma é formada por: ID do Curso + Período + Turno.
     * Isso garante que alunos do mesmo curso, período e turno compartilhem a mesma turma.
     *
     * Exemplo de código único: "42-3-N" → Curso 42, 3º período, Noturno
     *
     * @param array $studentData Dados brutos do aluno vindos da API
     * @return string            Código único da turma (ex: "42-3-N")
     */
    protected function syncTurma(array $studentData)
    {
        $courseId = $studentData['courseId']      ?? '0'; // ID do curso na API
        $periodo  = $studentData['currentPeriod'] ?? '1'; // Período atual do aluno
        $shift    = $studentData['shift']         ?? 'U'; // Turno: M=Manhã, V=Tarde, N=Noite, U=Geral

        // Monta a chave única combinando os três campos discriminadores da turma
        $codigoUnico = "{$courseId}-{$periodo}-{$shift}";

        // Converte a sigla do turno para o nome legível em português
        $nomeTurno = match($shift) {
            'N'     => 'NOITE',
            'M'     => 'MANHÃ',
            'V'     => 'TARDE',
            default => 'GERAL',
        };

        // Monta o nome da turma usando apenas a primeira parte do nome do curso (antes do " - ")
        // Exemplo: "3º Informática - NOITE"
        $nomeTurma = "{$periodo}º " . (explode(' - ', $studentData['courseName'] ?? '')[0]) . " - {$nomeTurno}";

        // Cria a turma se não existir, ou atualiza o nome se o código já estiver cadastrado
        Turma::updateOrCreate(['codigo' => $codigoUnico], ['nome' => $nomeTurma]);

        return $codigoUnico;
    }

    /**
     * Cria ou atualiza o registro de um Discente no banco de dados.
     *
     * A matrícula é usada como chave de identificação única do aluno.
     * Os demais campos são atualizados a cada sincronização.
     *
     * @param array  $studentData  Dados brutos do aluno vindos da API
     * @param string $codigoTurma  Código único da turma à qual o aluno pertence
     */
    protected function processStudent(array $studentData, string $codigoTurma)
    {
        Discente::updateOrCreate(
            // Critério de busca: matrícula única do aluno
            ['matricula' => $studentData['enrollment']],

            // Campos a serem criados ou atualizados
            [
                'nome'           => $studentData['fullName'] ?? null,
                'email_discente' => $studentData['email']    ?? null,

                // Converte a data de nascimento para o formato do banco (Y-m-d), se existir
                'data_nascimento' => isset($studentData['birthday'])
                    ? Carbon::parse($studentData['birthday'])->format('Y-m-d')
                    : null,

                // Apenas alunos com status "Matriculado" chegam até aqui (filtro em syncAllPages)
                'status_qa' => 'Matriculado',

                // Referência à turma específica (Curso + Período + Turno)
                'turma' => $codigoTurma,
            ]
        );
    }

    /**
     * Realiza uma requisição GET autenticada à API do Q-Acadêmico.
     *
     * A verificação SSL é desabilitada (`verify => false`) para ambientes
     * que possam ter certificados auto-assinados ou problemas de CA.
     *
     * @param string $endpoint Caminho do endpoint (ex: 'students')
     * @param array  $params   Parâmetros de query string (ex: ['page' => 0, 'size' => 50])
     * @return \Illuminate\Http\Client\Response|null
     */
    protected function makeApiRequest($endpoint, $params)
    {
        return Http::withOptions(['verify' => false]) // Desativa verificação SSL
            ->withHeaders([
                'Authorization' => $this->apiToken,   // Token de acesso à API
                'Accept'        => 'application/json', // Exige resposta em JSON
            ])
            ->get($this->apiBaseUrl . $endpoint, $params);
    }
}