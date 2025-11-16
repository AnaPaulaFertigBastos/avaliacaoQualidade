@extends('admin.layout')
@section('title','Editar Pergunta')
@section('content')
  <div class="card">
    <h1 class="h5 mb-3">Editar Pergunta</h1>
    @if($errors->any())
      <div class="alert alert-danger py-2">
        @foreach($errors->all() as $e)
          <div>{{ $e }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('admin.questions.update', $pergunta->id) }}" class="row g-3">
      @csrf
      @method('PUT')
      <div class="col-12">
        <label for="texto" class="form-label">Texto da pergunta</label>
        <textarea id="texto" name="texto" rows="3" class="form-control">{{ old('texto', $pergunta->texto) }}</textarea>
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $pergunta->status) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">Ativa</label>
        </div>
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="resposta_numerica" name="resposta_numerica" value="1" {{ old('resposta_numerica', $pergunta->resposta_numerica) ? 'checked' : '' }}>
          <label class="form-check-label" for="resposta_numerica">Resposta numérica (0 a 10)</label>
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary btn-sm">Cancelar</a>
      </div>
    </form>
  </div>
@endsection
