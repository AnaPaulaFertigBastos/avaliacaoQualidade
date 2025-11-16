@extends('admin.layout')
@section('title','Perguntas')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Perguntas</h1>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.questions.create') }}">Criar nova</a>
  </div>
  @if(session('success'))<div class="alert alert-success py-1">{{ session('success') }}</div>@endif
  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-sm align-middle mb-0">
        <thead class="table-light">
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
              <td><span class="badge {{ $q->status ? 'bg-success' : 'bg-secondary' }}">{{ $q->status ? 'Ativo' : 'Inativo' }}</span></td>
              <td><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.questions.edit', $q->id) }}">Editar</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
