<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Dispositivo;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AvaliacaoController extends Controller
{
    // mostra o formulário público (home)
    public function index()
    {
        $questions = Pergunta::where('status', true)->get();
        $devices = Dispositivo::where('status', true)->get();
        
        // reuso das views existentes: 'evaluation.index'
        return view('evaluation.index', ['questions' => $questions, 'devices' => $devices]);
    }

    // armazena respostas (uma avaliação gera várias linhas, uma por pergunta)
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|string|exists:dispositivos,id',
            'responses' => 'required|array',
            'responses.*' => 'required|integer|min:0|max:10',
            'feedback' => 'nullable|string|max:2000',
            'setor_id' => 'nullable|string|exists:setores,id',
        ]);

        $deviceId = $data['device_id'];
        $feedback = $data['feedback'] ?? null;
        $setorId = $data['setor_id'] ?? null;

        foreach ($data['responses'] as $questionId => $response) {
            Avaliacao::create([
                'id' => Str::uuid()->toString(),
                'setor_id' => $setorId,
                'pergunta_id' => $questionId,
                'dispositivo_id' => $deviceId,
                'resposta' => $response,
                'feedback_textual' => $feedback,
                'data' => now(),
            ]);
        }

        return view('evaluation.thankyou');
    }
}
