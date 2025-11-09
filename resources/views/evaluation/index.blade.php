<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Avaliação</title>
    
</head>
<body>
    <div class="card evaluation-card">
        <div class="title">
            <h1>Formulário de Avaliação</h1>
        </div>
        
        @if($selectedDeviceId == null || $selectedSetorId == null)
            <p>Insira, na URL, um Setor e Dispositivo válidos para prosseguir com a avaliação, respectivamente.</p>
        @else
            <p class="subtitle">Por favor, avalie de 0 (MUITO INSATISFEITO) a 10 (COMPLETAMENTE SATISFEITO).</p>
            <form method="POST" action="{{ route('evaluation.store') }}" id="evalForm">
                @csrf

                <div class="form-row">
                    
                    <!-- <label for="device_id">Dispositivo / Setor: {{$selectedSetorId}}</label>
                    @if(!empty($selectedDeviceId))
                        <input type="hidden" name="device_id" value="{{ $selectedDeviceId }}">
                        <p><strong>Dispositivo:</strong> {{ $selectedDeviceName ?? '—' }}</p>
                    @else
                        <select name="device_id" id="device_id" required>
                            <option value="">-- selecione --</option>
                            @foreach($devices as $d)
                                <option value="{{ $d->id }}">{{ $d->nome }}</option>
                            @endforeach
                        </select>
                    @endif -->

                    @if(!empty($selectedSetorId))
                        <input type="hidden" name="setor_id" value="{{ $selectedSetorId }}">
                    @endif
                </div>

                <hr>

                <div class="questions-wrap">
                    @foreach($questions as $q)
                        <div class="question" data-index="{{ $loop->index }}" aria-hidden="{{ $loop->index === 0 ? 'false' : 'true' }}">
                            <div class="question-inner">
                                <p class="question-number"><strong>{{ $loop->iteration }} / {{ $questions->count() }}</strong></p>
                                <p class="question-text"><strong>{{ $q->texto }}</strong></p>
                                @if ($q->resposta_numerica == false)
                                    <div class="feedback-row">
                                        <label for="feedback">Feedback adicional (opcional)</label><br>
                                        <textarea name="feedback" id="feedback" rows="4" style="width:100%"></textarea>
                                    </div>
                                @else
                                    <div class="scale" style="">
                                        @for($i = 0; $i <= 10; $i++)
                                            @php
                                                // Gera a cor de forma gradiente (vermelho → verde)
                                                // R vai de 255 → 0, G vai de 0 → 200 aproximadamente
                                                $r = 255 - ($i * 25);
                                                $g = $i * 20;
                                                $color = "rgb($r, $g, 0)";
                                            @endphp

                                            <label class="scale-label">
                                                <input type="radio" name="responses[{{ $q->id }}]" value="{{ $i }}" style="display: none;">
                                                <span class="scale-value"
                                                    style="
                                                        background-color: {{ $color }};
                                                    "
                                                    onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 0 8px rgba(0,0,0,0.3)';"
                                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';"
                                                >
                                                    {{ $i }}
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
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
    <link rel="stylesheet" href="{{ asset('css/evaluation.css') }}">
    <script src="{{ asset('js/evaluation.js') }}" defer></script>
    
</body>
</html>
