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
          @php($minOrder = $questions->min('ordem'))
          @php($maxOrder = $questions->max('ordem'))
          @foreach($questions as $q)
            <tr>
              <td>{{ $q->texto }}</td>
              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center questions-order-controls">
                  <form method="POST" action="{{ route('admin.questions.up', $q->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm order-btn" {{ $q->ordem == $minOrder ? 'disabled' : '' }} title="Mover para cima">↑</button>
                  </form>
                  <span class="order-number fw-semibold">{{ $q->ordem }}</span>
                  <form method="POST" action="{{ route('admin.questions.down', $q->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm order-btn" {{ $q->ordem == $maxOrder ? 'disabled' : '' }} title="Mover para baixo">↓</button>
                  </form>
                </div>
              </td>
              <td><span class="badge {{ $q->status ? 'bg-success' : 'bg-secondary' }}">{{ $q->status ? 'Ativo' : 'Inativo' }}</span></td>
              <td><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.questions.edit', $q->id) }}">Editar</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
