@extends('admin.layout')
@section('title','Login Admin')
@section('content')
  <div class="d-flex justify-content-center align-items-center" style="min-height:60vh;">
    <div class="card" style="max-width:420px;width:100%;">
      <h2 class="h5 mb-3" style="color:var(--cor-primaria);">Acesso ao Painel</h2>
      @if($errors->any())
        <div class="alert alert-danger py-1 mb-3">{{ $errors->first() }}</div>
      @endif
      <form method="POST" action="{{ route('admin.login.post') }}" class="row g-3">
        @csrf
        <div class="col-12">
          <label for="email" class="form-label">E-mail</label>
          <input id="email" name="email" type="email" required class="form-control" value="{{ old('email') }}">
        </div>
        <div class="col-12">
          <label for="password" class="form-label">Senha</label>
          <input id="password" type="password" name="password" required class="form-control">
        </div>
        <div class="col-12 d-flex justify-content-between align-items-center mt-2">
          <button type="submit" class="btn btn-primary btn-sm">Entrar</button>
          <span class="small text-muted">Somente usuários autorizados.</span>
        </div>
      </form>
    </div>
  </div>
@endsection
