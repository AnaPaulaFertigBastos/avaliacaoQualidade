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
    <table border="1" cellpadding="8" cellspacing="0">
      <thead><tr><th>#</th><th>Pergunta</th><th>Média</th></tr></thead>
      <tbody>
        @foreach($stats as $s)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $s['question']->text }}</td>
            <td>{{ $s['average'] ? number_format($s['average'],2,',','.') : '—' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
