<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gerenciar Perguntas</title>
  <style>body{font-family:Arial;padding:20px} table{width:100%;border-collapse:collapse} th,td{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
  <h1>Perguntas</h1>
  <p><a href="{{ route('admin.questions.create') }}">Criar nova pergunta</a> | <a href="{{ route('admin.dashboard') }}">Voltar</a></p>
  @if(session('success'))<div style="color:green">{{ session('success') }}</div>@endif
  <table>
    <thead><tr><th>#</th><th>Texto</th><th>Ordem</th><th>Ativa</th></tr></thead>
    <tbody>
      @foreach($questions as $q)
        <tr>
          <td>{{ $q->id }}</td>
          <td>{{ $q->text }}</td>
          <td>{{ $q->order }}</td>
          <td>{{ $q->active ? 'Sim' : 'Não' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
