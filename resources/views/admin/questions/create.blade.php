@extends('admin.layout')
@section('title','Criar Pergunta')
@section('content')
  <div class="card">
    <h1 class="h5 mb-3">Criar Pergunta</h1>
    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 mb-3">
        <strong>Erro:</strong> Verifique os campos abaixo.
      </div>
    @endif
    <form method="POST" action="{{ route('admin.questions.store') }}" class="row g-3">
      @csrf
      <div class="col-12">
        <label for="texto" class="form-label">Texto da Pergunta</label>
        <textarea id="texto" name="texto" required rows="4" class="form-control">{{ old('texto') }}</textarea>
      </div>
      <div class="col-12 col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">Ativa</label>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="resposta_numerica" name="resposta_numerica" value="1" {{ old('resposta_numerica', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="resposta_numerica">Resposta Numérica (0–10)</label>
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary btn-sm">Cancelar</a>
      </div>
    </form>
  </div>
@endsection
