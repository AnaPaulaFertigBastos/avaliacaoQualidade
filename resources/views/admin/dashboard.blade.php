@extends('admin.layout')
@section('title','Dashboard')
@section('content')
  <div class="card mb-4">
    <h1 class="h5 mb-3">Painel Administrativo</h1>
    <p class="text-muted mb-0">Visão geral das avaliações.</p>
  </div>
  <div class="row g-4">
    <div class="col-12 col-xl-6">
      <div class="card">
        <h2 class="h6">Média por Pergunta</h2>
        <canvas id="chartMedias" class="chart-canvas"></canvas>
      </div>
    </div>
    <div class="col-12 col-xl-6">
      <div class="card">
        <h2 class="h6">Distribuição Geral de Pontuações</h2>
        <canvas id="chartDistribuicao" class="chart-canvas"></canvas>
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
    const labelsMedias = @json($chartLabels);
    const valoresMedias = @json($chartAverages);
    const labelsDistrib = @json($scoreDistributionLabels);
    const valoresDistrib = @json($scoreDistributionValues);

    function createBarChart(){
      new Chart(document.getElementById('chartMedias'), {
        type: 'bar',
        data: {
          labels: labelsMedias,
          datasets: [{
            label: 'Média',
            data: valoresMedias,
            backgroundColor: 'rgba(54,162,235,0.6)',
            borderColor: 'rgba(54,162,235,1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: true },
            tooltip: { enabled: true }
          },
          scales: {
            y: { beginAtZero: true, suggestedMax: 10 }
          }
        }
      });
    }

    function createDistribChart(){
      new Chart(document.getElementById('chartDistribuicao'), {
        type: 'doughnut',
        data: {
          labels: labelsDistrib.map(l => l.toString()),
          datasets: [{
            label: 'Total respostas',
            data: valoresDistrib,
            backgroundColor: labelsDistrib.map(score => {
              const g = Math.round((score/10) * 180);
              const r = 200 - g;
              return `rgba(${r},${g},60,0.7)`;
            })
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'bottom' } }
        }
      });
    }

    createBarChart();
    createDistribChart();
  </script>
@endpush
