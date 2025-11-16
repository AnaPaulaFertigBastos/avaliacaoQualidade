<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login</title>
  <style>body{font-family:Arial;background:#f5f5f5;padding:40px}.card{max-width:420px;margin:0 auto;background:#fff;padding:20px;border-radius:6px}</style>
</head>
<body>
  <div class="card">
    <h2>Painel Administrativo - Login</h2>
    @if($errors->any())<div style="color:red">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('admin.login.post') }}">
      @csrf
      <div><label>E-mail</label><br><input name="email" type="email" required></div>
      <div style="margin-top:8px"><label>Senha</label><br><input type="password" name="password" required></div>
      <div style="margin-top:12px"><button type="submit">Entrar</button></div>
    </form>
  </div>
</body>
</html>
