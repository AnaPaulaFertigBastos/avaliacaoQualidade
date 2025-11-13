<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Obrigado</title>
  
</head>
<body>
  <div class="card evaluation-card">
    <h1>Obrigado pela sua avaliação</h1>
    <p>O Estabelecimento agradece sua resposta e ela é muito importante para nós, pois nos ajuda a melhorar continuamente nossos serviços.</p>
  <div class="actions">
    {{-- Voltar para o formulário de avaliação (rota nomeada 'avaliacao.form').
       Se recebemos setorId/deviceId, monta a URL com os parâmetros para reavaliar no mesmo local/dispositivo. --}}
    @if(isset($setorId) && isset($deviceId) && $setorId && $deviceId)
      <a class="btn submit-btn" href="{{ route('avaliacao.form', ['setorId' => $setorId, 'dispositivoId' => $deviceId]) }}">Avaliar novamente</a>
    @else
      <a class="btn submit-btn" href="{{ route('avaliacao.form') }}">Avaliar novamente</a>
    @endif
  </div>
  </div>
  <link rel="stylesheet" href="{{ asset('css/evaluation.css') }}">
  <script src="{{ asset('js/thankyou.js') }}" defer></script>
</body>
</html>
