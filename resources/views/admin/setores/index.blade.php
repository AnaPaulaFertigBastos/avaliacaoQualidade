@extends('admin.layout')
@section('title','Setores')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Setores</h1>
  </div>
  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-sm align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:360px">ID</th>
            <th>Descrição</th>
            <th>Ativo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($setores as $s)
            <tr>
              <td><code class="small">{{ $s->id }}</code></td>
              <td>{{ $s->descricao }}</td>
              <td><span class="badge {{ $s->ativo ? 'bg-success' : 'bg-secondary' }}">{{ $s->ativo ? 'Sim' : 'Não' }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
