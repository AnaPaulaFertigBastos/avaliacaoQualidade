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
  <p>
    <a href="{{ route('admin.questions.create') }}">Criar nova pergunta</a> |
    <a href="{{ route('admin.dashboard') }}">Voltar</a> |
    <form action="{{ route('admin.logout') }}" method="POST" style="display:inline">
      @csrf
      <button type="submit" style="background:none;border:none;color:#06c;cursor:pointer;padding:0">Sair</button>
    </form>
  </p>
  @if(session('success'))<div style="color:green">{{ session('success') }}</div>@endif
  <table>
    <thead>
      <tr>
        <th>Texto</th>
        <th>Ordem</th>
        <th>Ativa</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($questions as $q)
        <tr>
          <td>{{ $q->texto }}</td>
          <td>{{ $q->order }}</td>
          <td>{{ $q->status ? 'Ativo' : 'Inativo' }}</td>
          <td>
            <a href="{{ route('admin.questions.edit', $q->id) }}">Editar</a>
            |
            <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Excluir esta pergunta? Esta ação não pode ser desfeita.');">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:none;border:none;color:#b00;cursor:pointer;padding:0">Excluir</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
