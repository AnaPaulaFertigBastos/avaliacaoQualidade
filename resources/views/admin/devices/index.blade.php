<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dispositivos</title>
  <style>body{font-family:Arial;padding:20px} table{width:100%;border-collapse:collapse} th,td{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
  <h1>Dispositivos</h1>
  <p>
    <a href="{{ route('admin.devices.create') }}">Cadastrar dispositivo</a> |
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
        <th>Nome</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($devices as $d)
        <tr>
          <td>{{ $d->nome }}</td>
          <td>{{ $d->status ? 'Ativo' : 'Inativo' }}</td>
          <td>
            <a href="{{ route('admin.devices.edit', $d->id) }}">Editar</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
