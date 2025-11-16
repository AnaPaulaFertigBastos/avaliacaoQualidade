<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <style>body{font-family:Arial;padding:20px} .card{background:#fff;padding:20px;border-radius:6px}</style>
</head>
<body>
  <div class="card">
    <h1>Painel Administrativo</h1>
    <p>
      <a href="{{ route('admin.questions.index') }}">Gerenciar Perguntas</a> |
      <a href="{{ route('admin.devices.index') }}">Gerenciar Dispositivos</a> |
      <form action="{{ route('admin.logout') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;color:#06c;cursor:pointer;padding:0">Sair</button>
      </form>
    </p>

    <h2>Média por Pergunta</h2>
    <canvas id="chartMedias" style="max-width: 900px; width:100%; height:320px; margin-bottom:24px;"></canvas>
    <h2>Distribuição Geral de Pontuações</h2>
    <canvas id="chartDistribuicao" style="max-width: 900px; width:100%; height:320px; margin-bottom:24px;"></canvas>
    <h2>Dados Tabulares</h2>
    <table border="1" cellpadding="8" cellspacing="0">
      <thead><tr><th>#</th><th>Pergunta</th><th>Média</th></tr></thead>
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
</body>
</html>
