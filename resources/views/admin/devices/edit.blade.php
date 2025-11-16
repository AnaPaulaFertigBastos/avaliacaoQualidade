@extends('admin.layout')
@section('title','Editar Dispositivo')
@section('content')
  <div class="card">
    <h1 class="h5 mb-3">Editar Dispositivo</h1>
    @if($errors->any())
      <div class="alert alert-danger py-2">
        @foreach($errors->all() as $e)
          <div>{{ $e }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('admin.devices.update', $device->id) }}" class="row g-3">
      @csrf
      @method('PUT')
      <div class="col-12">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" id="nome" name="nome" value="{{ old('nome', $device->nome) }}" required class="form-control">
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $device->status) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">Ativo</label>
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
        <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-secondary btn-sm">Cancelar</a>
      </div>
    </form>
  </div>
@endsection
