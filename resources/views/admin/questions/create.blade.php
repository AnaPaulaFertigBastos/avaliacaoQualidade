@extends('admin.layout')
@section('title','Criar Pergunta')
@section('content')
  <div class="card">
    <h1 class="h5 mb-3">Criar Pergunta</h1>
    <form method="POST" action="{{ route('admin.questions.store') }}" class="row g-3">
      @csrf
      <div class="col-12">
        <label for="texto" class="form-label">Texto</label>
        <textarea id="texto" name="text" required rows="4" class="form-control"></textarea>
      </div>
      <div class="col-12 col-md-4">
        <label for="order" class="form-label">Ordem</label>
        <input id="order" name="order" type="number" class="form-control">
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="ativa" name="active" value="1" checked>
          <label class="form-check-label" for="ativa">Ativa</label>
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary btn-sm">Cancelar</a>
      </div>
    </form>
  </div>
@endsection
