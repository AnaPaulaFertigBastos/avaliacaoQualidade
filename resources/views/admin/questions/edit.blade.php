<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Editar Pergunta</title>
  <style>body{font-family:Arial;padding:20px} .card{max-width:720px;margin:0 auto;background:#fff;padding:20px;border-radius:6px}</style>
</head>
<body>
  <div class="card">
    <h1>Editar Pergunta</h1>
    @if($errors->any())
      <div style="color:#b00; margin-bottom:12px;">
        @foreach($errors->all() as $e)
          <div>{{ $e }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('admin.questions.update', $pergunta->id) }}">
      @csrf
      @method('PUT')
      <div class="form-row" style="margin-bottom:12px;">
        <label for="texto"><strong>Texto da pergunta</strong></label><br>
        <textarea id="texto" name="texto" rows="3" style="width:100%;box-sizing:border-box;">{{ old('texto', $pergunta->texto) }}</textarea>
      </div>
      <div class="form-row" style="margin-bottom:12px;">
        <label><input type="checkbox" name="status" value="1" {{ old('status', $pergunta->status) ? 'checked' : '' }}> Ativa</label>
      </div>
      <div class="form-row" style="margin-bottom:12px;">
        <label><input type="checkbox" name="resposta_numerica" value="1" {{ old('resposta_numerica', $pergunta->resposta_numerica) ? 'checked' : '' }}> Resposta numérica (0 a 10)</label>
      </div>
      <div class="actions" style="display:flex;gap:10px;align-items:center;">
        <button type="submit">Salvar</button>
        <a href="{{ route('admin.questions.index') }}">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
