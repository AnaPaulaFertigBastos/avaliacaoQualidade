<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Criar Pergunta</title>
  <style>body{font-family:Arial;padding:20px}.card{background:#fff;padding:20px;border-radius:6px}</style>
</head>
<body>
  <div class="card">
    <h1>Criar Pergunta</h1>
    <form method="POST" action="{{ route('admin.questions.store') }}">
      @csrf
      <div><label>Texto</label><br><textarea name="text" required rows="4" style="width:100%"></textarea></div>
      <div style="margin-top:8px"><label>Ordem</label><br><input name="order" type="number"></div>
      <div style="margin-top:8px"><label><input type="checkbox" name="active" value="1" checked> Ativa</label></div>
      <div style="margin-top:12px"><button type="submit">Salvar</button> <a href="{{ route('admin.questions.index') }}">Cancelar</a></div>
    </form>
  </div>
</body>
</html>
