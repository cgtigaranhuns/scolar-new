<x-filament-panels::page>
    @php
        $conselho = $this->record;
        $turma = $conselho->turma;
        $discentesConselho = $conselho->discentesConselho()->with('discente')->get();
    @endphp

    <div class="fi-page-content">
        <div class="fi-container">
            <!-- Header do Conselho -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Informações do Conselho de Classe</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Turma:</strong></label>
                            <p>{{ $turma->descricao ?? $turma->nome ?? 'Não informada' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Descrição:</strong></label>
                            <p>{{ $conselho->descricao }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label"><strong>Unidade:</strong></label>
                            <p>{{ $conselho->unidade }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Data Início:</strong></label>
                            <p>{{ $conselho->data_inicio ? \Carbon\Carbon::parse($conselho->data_inicio)->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Data Fim:</strong></label>
                            <p>{{ $conselho->data_fim ? \Carbon\Carbon::parse($conselho->data_fim)->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Status:</strong></label>
                            <p>
                                @switch($conselho->status)
                                    @case(1)
                                        <span class="badge bg-warning">Aberto</span>
                                        @break
                                    @case(2)
                                        <span class="badge bg-info">Em Andamento</span>
                                        @break
                                    @case(3)
                                        <span class="badge bg-success">Finalizado</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Desconhecido</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legenda das Áreas -->
            <div class="card mb-4">
                <div class="card-body bg-light">
                    <h5 class="card-title">Definições das Áreas:</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Área 1</strong> - Ciências da Natureza, Matemática e suas Tecnologias
                        </div>
                        <div class="col-md-3">
                            <strong>Área 2</strong> - Linguagens, Códigos e suas Tecnologias
                        </div>
                        <div class="col-md-3">
                            <strong>Área 3</strong> - Ciências Humanas e suas Tecnologias
                        </div>
                        <div class="col-md-3">
                            <strong>Área 4</strong> - Área Técnica
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Discentes -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Estudantes do Conselho</h4>
                </div>
                <div class="card-body">
                    @if($discentesConselho->isEmpty())
                        <div class="alert alert-warning">
                            Nenhum discente cadastrado neste conselho.
                        </div>
                    @else
                        <form method="POST" action="{{ route('filament.resources.conselhos.pages.avaliacao.update', $conselho->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">Foto</th>
                                            <th rowspan="2" class="text-center align-middle">Matrícula</th>
                                            <th rowspan="2" class="text-center align-middle">Nome do Estudante</th>
                                            <th rowspan="2" class="text-center align-middle">Informações Complementares</th>
                                            <th colspan="4" class="text-center">Avaliação por Área</th>
                                            <th rowspan="2" class="text-center align-middle">Avaliação Geral do Discente</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Área 1</th>
                                            <th class="text-center">Área 2</th>
                                            <th class="text-center">Área 3</th>
                                            <th class="text-center">Área 4</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($discentesConselho as $discenteConselho)
                                            @php
                                                $discente = $discenteConselho->discente;
                                                $fotoPath = $discente->foto ? 'storage/' . $discente->foto : 'images/sem-foto.png';
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <img src="{{ asset($fotoPath) }}" 
                                                         alt="Foto de {{ $discente->nome }}" 
                                                         width="80" 
                                                         height="100" 
                                                         class="img-thumbnail"
                                                         style="object-fit: cover;">
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ $discente->matricula }}</strong>
                                                </td>
                                                <td>
                                                    {{ $discente->nome }}
                                                </td>
                                                <td>
                                                    <textarea class="form-control" 
                                                              rows="3" 
                                                              readonly
                                                              placeholder="Informações complementares...">{{ $discente->informacoes_adicionais ?? '-' }}</textarea>
                                                </td>
                                                <td class="text-center">
                                                    <div class="small text-muted mb-1"><strong>Participação:</strong> {{ $discenteConselho->nt_a1_participacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Interesse:</strong> {{ $discenteConselho->nt_a1_interesse ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Organização:</strong> {{ $discenteConselho->nt_a1_organizacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Comprometimento:</strong> {{ $discenteConselho->nt_a1_comprometimento ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Disciplina:</strong> {{ $discenteConselho->nt_a1_disciplina ?? '-' }}</div>
                                                    <div class="small text-muted"><strong>Cooperação:</strong> {{ $discenteConselho->nt_a1_cooperacao ?? '-' }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="small text-muted mb-1"><strong>Participação:</strong> {{ $discenteConselho->nt_a2_participacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Interesse:</strong> {{ $discenteConselho->nt_a2_interesse ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Organização:</strong> {{ $discenteConselho->nt_a2_organizacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Comprometimento:</strong> {{ $discenteConselho->nt_a2_comprometimento ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Disciplina:</strong> {{ $discenteConselho->nt_a2_disciplina ?? '-' }}</div>
                                                    <div class="small text-muted"><strong>Cooperação:</strong> {{ $discenteConselho->nt_a2_cooperacao ?? '-' }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="small text-muted mb-1"><strong>Participação:</strong> {{ $discenteConselho->nt_a3_participacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Interesse:</strong> {{ $discenteConselho->nt_a3_interesse ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Organização:</strong> {{ $discenteConselho->nt_a3_organizacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Comprometimento:</strong> {{ $discenteConselho->nt_a3_comprometimento ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Disciplina:</strong> {{ $discenteConselho->nt_a3_disciplina ?? '-' }}</div>
                                                    <div class="small text-muted"><strong>Cooperação:</strong> {{ $discenteConselho->nt_a3_cooperacao ?? '-' }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="small text-muted mb-1"><strong>Participação:</strong> {{ $discenteConselho->nt_a4_participacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Interesse:</strong> {{ $discenteConselho->nt_a4_interesse ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Organização:</strong> {{ $discenteConselho->nt_a4_organizacao ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Comprometimento:</strong> {{ $discenteConselho->nt_a4_comprometimento ?? '-' }}</div>
                                                    <div class="small text-muted mb-1"><strong>Disciplina:</strong> {{ $discenteConselho->nt_a4_disciplina ?? '-' }}</div>
                                                    <div class="small text-muted"><strong>Cooperação:</strong> {{ $discenteConselho->nt_a4_cooperacao ?? '-' }}</div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="discentes[{{ $loop->index }}][id]" value="{{ $discenteConselho->id }}">
                                                    <textarea name="discentes[{{ $loop->index }}][avaliacao_geral_discente]" 
                                                              class="form-control" 
                                                              rows="4"
                                                              maxlength="1000"
                                                              placeholder="Avaliação geral do discente...">{{ $discenteConselho->avaliacao_geral_discente ?? '' }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('filament.resources.conselhos.index') }}" class="btn btn-warning">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Salvar Avaliações
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
