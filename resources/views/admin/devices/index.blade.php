@extends('admin.layout')
@section('title','Dispositivos')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Dispositivos</h1>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.devices.create') }}">Cadastrar</a>
  </div>
  @if(session('success'))<div class="alert alert-success py-1">{{ session('success') }}</div>@endif
  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-sm align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:360px">ID</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($devices as $d)
            <tr>
              <td><code class="small">{{ $d->id }}</code></td>
              <td>{{ $d->nome }}</td>
              <td><span class="badge {{ $d->status ? 'bg-success' : 'bg-secondary' }}">{{ $d->status ? 'Ativo' : 'Inativo' }}</span></td>
              <td><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.devices.edit', $d->id) }}">Editar</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
