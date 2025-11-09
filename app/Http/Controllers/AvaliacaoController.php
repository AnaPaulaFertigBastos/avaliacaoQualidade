<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Dispositivo;
use App\Models\Setor;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AvaliacaoController extends Controller
{
    // mostra o formulário público (home)
    public function index($setorId = null, $dispositivoId = null)
    {
        $questions = Pergunta::where('status', true)->get();
        $devices = Dispositivo::where('status', true)->get();

        $selectedDeviceId = null;
        $selectedSetorId = null;
        $selectedDeviceName = null;

        // Se vier um dispositivo: só tentamos buscar quando for um UUID válido.
        // Se o valor não for um UUID, tratamos como 'null' (não pré-selecionado).
        if ($dispositivoId && Str::isUuid($dispositivoId)) {
            $device = Dispositivo::find($dispositivoId);
            if (! $device) {
                // GUID informado e válido, mas não existe no banco -> 404 claro
                abort(404, "Dispositivo '{$dispositivoId}' não encontrado");
            }

            $selectedDeviceId = $device->id;
            $selectedDeviceName = $device->nome ?? null;
        } else {
            // não é UUID ou não informado => ignora
            $selectedDeviceId = null;
            $selectedDeviceName = null;
        }

        // Mesmo comportamento para setor: somente busca se for UUID válido
        if ($setorId && Str::isUuid($setorId)) {
            $setor = Setor::find($setorId);
            if (! $setor) {
                abort(404, "Setor '{$setorId}' não encontrado");
            }

            $selectedSetorId = $setor->id;
        } else {
            $selectedSetorId = null;
        }

        // reuso das views existentes: 'evaluation.index'
        return view('evaluation.index', [
            'questions' => $questions,
            'devices' => $devices,
            'selectedDeviceId' => $selectedDeviceId,
            'selectedSetorId' => $selectedSetorId,
            'selectedDeviceName' => $selectedDeviceName,
        ]);
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
