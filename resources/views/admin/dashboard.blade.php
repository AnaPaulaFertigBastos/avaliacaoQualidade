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
    // Rótulos completos das perguntas (podem ser longos)
    let fullLabelsMedias = @json($chartLabels);
    // Valores das médias
    let valoresMedias = @json($chartAverages);
    let labelsDistrib = @json($scoreDistributionLabels);
    let valoresDistrib = @json($scoreDistributionValues);

    // Função para truncar rótulos muito extensos e evitar grande deslocamento do gráfico
    function truncarTexto(label, max = 38){
      if(!label) return '';
      return label.length > max ? label.slice(0, max - 3) + '...' : label;
    }

    // Rótulos usados efetivamente no gráfico (truncados)
    let labelsMedias = fullLabelsMedias.map(l => truncarTexto(l));

    let chartMediasInstance = null;
    let chartDistribInstance = null;

    function createBarChart(){
      const ctxMedias = document.getElementById('chartMedias');
      if(!ctxMedias){ console.warn('Canvas chartMedias não encontrado'); return; }
      if(chartMediasInstance){ chartMediasInstance.destroy(); }
      chartMediasInstance = new Chart(ctxMedias, {
        type: 'bar',
        data: {
          labels: labelsMedias,
          datasets: [{
            label: 'Média',
            data: valoresMedias,
            backgroundColor: 'rgba(45,106,123,0.55)',
            borderColor: 'rgba(45,106,123,1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: true },
            tooltip: {
              enabled: true,
              callbacks: {
                // Mostra o rótulo completo no título do tooltip
                title: (items) => {
                  if(!items || !items.length) return '';
                  const idx = items[0].dataIndex;
                  return fullLabelsMedias[idx] || '';
                }
              }
            }
          },
          scales: {
            y: { beginAtZero: true, suggestedMax: 10 },
            x: {
              ticks: {
                callback: function(value){
                  // value é o índice; retorna rótulo truncado já presente
                  return labelsMedias[value];
                }
              }
            }
          }
        }
      });
    }

    function createDistribChart(){
      const ctxDistrib = document.getElementById('chartDistribuicao');
      if(!ctxDistrib){ console.warn('Canvas chartDistribuicao não encontrado'); return; }
      if(chartDistribInstance){ chartDistribInstance.destroy(); }
     
      function getScoreColor(score){
        const pct = score / 10; // 0..1
        
        let hue;
        if(pct <= 0.5){
          hue = 0 + (60 * (pct / 0.5)); // 0..60
        } else {
          hue = 60 + (60 * ((pct - 0.5) / 0.5)); // 60..120
        }
        const saturation = 65; // moderado
        const lightness = 58 + (pct * 6);
          bg: `hsla(${hue}, ${saturation}%, ${lightness}%, 0.70)`,
          border: `hsla(${hue}, ${saturation}%, ${lightness - 10}%, 0.90)`
        };
      }
      const distColors = labelsDistrib.map(score => getScoreColor(score));
      chartDistribInstance = new Chart(ctxDistrib, {
        type: 'doughnut',
        data: {
          labels: labelsDistrib.map(l => l.toString()),
          datasets: [{
            label: 'Total respostas',
            data: valoresDistrib,
            backgroundColor: distColors.map(c => c.bg),
            borderColor: distColors.map(c => c.border),
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                // exibe legenda com a cor direta sem tentar usar uma paleta distinta
                generateLabels: chart => {
                  const data = chart.data;
                  if(!data.labels) return [];
                  return data.labels.map((label, i) => ({
                    text: label,
                    fillStyle: distColors[i].bg,
                    strokeStyle: distColors[i].border,
                    hidden: false,
                    index: i
                  }));
                }
              }
            },
            tooltip: {
              callbacks: {
                label: ctx => {
                  const score = ctx.label;
                  const qtd = ctx.parsed;
                  return `Nota ${score}: ${qtd}`;
                }
              }
            }
          },
          cutout: '55%'
        }
      });
    }

    async function atualizarPorSetor(setorId){
      try {
        const url = setorId ? `{{ route('admin.dashboard.dados') }}/${setorId}` : `{{ route('admin.dashboard.dados') }}`;
        const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
        if(!resp.ok){ console.warn('Falha ao buscar dados'); return; }
        const data = await resp.json();
        fullLabelsMedias = data.labels;
        labelsMedias = fullLabelsMedias.map(l => truncarTexto(l));
        valoresMedias = data.averages;
        labelsDistrib = data.scoreLabels;
        valoresDistrib = data.scoreValues;
        createBarChart();
        createDistribChart();
      } catch(e){ console.error('Erro atualização setor', e); }
    }

    document.getElementById('filtroSetor').addEventListener('change', e => {
      const v = e.target.value.trim();
      atualizarPorSetor(v !== '' ? v : null);
    });

    createBarChart();
    createDistribChart();
  </script>
@endpush
