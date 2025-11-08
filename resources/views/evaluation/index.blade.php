<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Avaliação</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;background:#f5f5f5;padding:20px}
        .card{max-width:900px;margin:0 auto;background:#fff;padding:20px;border-radius:6px}
        .question{margin-bottom:18px}
        .scale input{margin-right:6px}
        footer{margin-top:20px;font-size:0.9em;color:#666}
        .btn{display:inline-block;padding:10px 14px;background:#007bff;color:#fff;border-radius:4px;text-decoration:none}
    </style>
</head>
<body>
    <div class="card">
        <h1>Formulário de Avaliação</h1>
        <p>Por favor avalie de 0 (MUITO INSATISFEITO) a 10 (COMPLETAMENTE SATISFEITO).</p>

        <form method="POST" action="{{ route('evaluation.store') }}" id="evalForm">
            @csrf

            <div>
                <label for="device_id">Dispositivo / Setor:</label>
                <select name="device_id" id="device_id" required>
                    <option value="">-- selecione --</option>
                    @foreach($devices as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <hr>

            @foreach($questions as $q)
                <div class="question">
                    <p><strong>{{ $loop->iteration }}. {{ $q->text }}</strong></p>
                    <div class="scale">
                        @for($i=0;$i<=10;$i++)
                            <label>
                                <input type="radio" name="responses[{{ $q->id }}]" value="{{ $i }}"> {{ $i }}
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach

            <div>
                <label for="feedback">Feedback adicional (opcional)</label><br>
                <textarea name="feedback" id="feedback" rows="4" style="width:100%"></textarea>
            </div>

            <div style="margin-top:12px">
                <button class="btn" type="submit">Enviar Avaliação</button>
            </div>
        </form>

        <footer>
            Sua avaliação espontânea é anônima, nenhuma informação pessoal é solicitada ou armazenada.
        </footer>
    </div>

    <script>
        // basic client-side validation - ensure one answer per question
        document.getElementById('evalForm').addEventListener('submit', function(e){
            const questions = @json($questions->pluck('id'));
            for(const q of questions){
                if(!document.querySelector('input[name="responses['+q+']"]:checked')){
                    e.preventDefault();
                    alert('Por favor responda todas as perguntas.');
                    return false;
                }
            }
        });
    </script>
</body>
</html>
