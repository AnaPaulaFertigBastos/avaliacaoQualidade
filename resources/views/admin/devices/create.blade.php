<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Novo Dispositivo</title>
  <style>body{font-family:Arial;padding:20px} .card{max-width:720px;margin:0 auto;background:#fff;padding:20px;border-radius:6px}</style>
</head>
<body>
  <div class="card">
    <h1>Cadastrar Dispositivo</h1>
    @if($errors->any())
      <div style="color:#b00; margin-bottom:12px;">
        @foreach($errors->all() as $e)
          <div>{{ $e }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('admin.devices.store') }}">
      @csrf
      <div class="form-row" style="margin-bottom:12px;">
        <label for="nome"><strong>Nome</strong></label><br>
        <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required style="width:100%;padding:8px;box-sizing:border-box;">
      </div>
      <div class="form-row" style="margin-bottom:12px;">
        <label><input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}> Ativo</label>
      </div>
      <div class="actions" style="display:flex;gap:10px;align-items:center;">
        <button type="submit">Salvar</button>
        <a href="{{ route('admin.devices.index') }}">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
