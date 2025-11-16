@extends('admin.layout')
@section('title','Dashboard')
@section('content')
  <div class="card mb-4">
    <h1 class="h5 mb-3">Painel Administrativo</h1>
    <p class="text-muted mb-0">Visão geral das avaliações.</p>
  </div>
  <div class="row g-4 align-items-stretch">
    <div class="col-12 col-xl-6">
      <div class="card h-100 mb-0">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 gap-3">
          <h2 class="h6 mb-0">Média por Pergunta</h2>
          <div class="d-flex align-items-center gap-2">
            <label for="filtroSetor" class="form-label mb-0">Setor:</label>
            <select id="filtroSetor" class="form-select form-select-sm" style="min-width:200px">
              <option value="">Todos</option>
              @foreach($setores as $s)
                <option value="{{ $s->id }}">{{ $s->descricao }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="chart-wrapper"><canvas id="chartMedias" class="chart-canvas"></canvas></div>
      </div>
    </div>
    <div class="col-12 col-xl-6">
      <div class="card h-100">
        <h2 class="h6">Distribuição de Pontuações</h2>
        <div class="chart-wrapper"><canvas id="chartDistribuicao" class="chart-canvas"></canvas></div>
      </div>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="h6 mb-0">Respostas Textuais Recentes</h2>
          <a href="{{ route('admin.respostas.textuais.index') }}" class="btn btn-outline-secondary btn-sm">Ver todas</a>
        </div>
        @if($textualSamples->isEmpty())
          <p class="text-muted mb-0">Não há respostas textuais registradas.</p>
        @else
          <ul class="list-group list-group-flush">
            @foreach($textualSamples as $sample)
              <li class="list-group-item px-0">
                <div class="small text-muted mb-1"><strong>Pergunta:</strong> {{ $sample->pergunta->texto }}</div>
                <div><strong>Resposta:</strong> {{ Str::limit($sample->feedback_textual, 160) }}</div>
                <div class="text-muted mt-1" style="font-size:.75rem;">{{ ($sample->data instanceof \Carbon\Carbon) ? $sample->data->format('d/m/Y H:i') : \Carbon\Carbon::parse($sample->data)->format('d/m/Y H:i') }}</div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
    <div class="col-12">
      <div class="card">
        <h2 class="h6">Dados Tabulares</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-sm mb-0">
            <thead class="table-light"><tr><th>#</th><th>Pergunta</th><th>Média</th></tr></thead>
            <tbody>
              @foreach($stats as $s)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $s['question']->texto }}</td>
                  <td>{{ $s['average'] ? number_format($s['average'],2,',','.') : '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    window.DASHBOARD_DATA = {
      chartLabels: @json($chartLabels),
      chartAverages: @json($chartAverages),
      scoreLabels: @json($scoreDistributionLabels),
      scoreValues: @json($scoreDistributionValues),
      dadosBaseUrl: "{{ route('admin.dashboard.dados') }}"
    };
  </script>
  <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
