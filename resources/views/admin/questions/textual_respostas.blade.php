@extends('admin.layout')
@section('title','Respostas Textuais')
@section('content')
  <div class="card mb-4">
    <h1 class="h5 mb-2">Respostas Textuais</h1>
    <p class="text-muted mb-0">Listagem completa de respostas para perguntas com resposta não numérica.</p>
  </div>
  <div class="card">
    @if($avaliacoesTextuais->isEmpty())
      <p class="text-muted mb-0">Nenhuma resposta textual encontrada.</p>
    @else
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:60px;">Data</th>
              <th>Pergunta</th>
              <th>Resposta</th>
              <th style="width:110px;">Setor</th>
              <th style="width:110px;">Dispositivo</th>
            </tr>
          </thead>
          <tbody>
            @foreach($avaliacoesTextuais as $av)
              <tr>
                <td style="white-space:nowrap; font-size:.75rem;">{{ $av->data->format('d/m/Y H:i') }}</td>
                <td style="width:260px;">{{ $av->pergunta->texto }}</td>
                <td>{{ $av->feedback_textual }}</td>
                <td>{{ optional($av->setor)->descricao ?? '—' }}</td>
                <td>{{ optional($av->dispositivo)->nome ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        {{ $avaliacoesTextuais->links() }}
      </div>
    @endif
    <div class="mt-3">
      <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Voltar ao Dashboard</a>
    </div>
  </div>
@endsection
