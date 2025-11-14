<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Avaliação</title>
    <link rel="stylesheet" href="{{ asset('css/evaluation.css') }}">
</head>
<body>
    <div class="card evaluation-card">
        <div class="title">
            <h1>Formulário de Avaliação</h1>
        </div>
        
        @if($selectedDeviceId == null || $selectedSetorId == null)
            <p>Insira, na URL, um Setor e Dispositivo válidos para prosseguir com a avaliação, respectivamente. Exemplo: /avaliacao/{setorId}/{dispositivoId}</p>
        @else
            {{-- exibe erros de validação, se houver --}}
            @if($errors->any())
                <div class="errors" style="color:#b00; margin-bottom:1rem;">
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="subtitle">Por favor, avalie de 0 (MUITO INSATISFEITO) a 10 (COMPLETAMENTE SATISFEITO).</p>
            <form method="POST" action="{{ route('avaliacao.salvar') }}" id="evalForm">
                @csrf

                <div class="form-row">
                    @if(!empty($selectedSetorId))
                        <input type="hidden" name="setor_id" value="{{ $selectedSetorId }}">
                    @endif

                    @if(!empty($selectedDeviceId))
                        <input type="hidden" name="device_id" value="{{ $selectedDeviceId }}">
                    @endif
                </div>

                <hr>

                {{-- container vazio: perguntas serão carregadas por AJAX --}}
                <div class="questions-wrap" id="questionsWrap"
                     data-setor="{{ $selectedSetorId }}" data-device="{{ $selectedDeviceId }}">
                    <p class="loading">Carregando perguntas…</p>
                </div>

                

                <div class="actions">
                    <button type="button" id="prevBtn" class="nav-btn" aria-label="Anterior" hidden>&larr;</button>
                    <button type="button" id="nextBtn" class="nav-btn" aria-label="Próxima">&rarr;</button>
                    <button class="btn submit-btn" type="submit" style="display:none">Enviar Avaliação</button>
                </div>
            </form>
        @endif
    </div>
    <footer>
        Sua avaliação espontânea é anônima, nenhuma informação pessoal é solicitada ou armazenada.
    </footer>
    
    <script src="{{ asset('js/evaluation.js') }}" defer></script>
    
</body>
</html>
