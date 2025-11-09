<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Models\Dispositivo;
use App\Models\Setor;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // validação de forma declarativa primeiro
        $data = $request->validate([
            'device_id' => 'required|string|exists:dispositivos,id',
            'responses' => 'required|array',
            // 'responses.*' => 'required',
            'setor_id' => 'nullable|string|exists:setores,id',
        ]);

        // sanitização básica: trim dos ids
        $deviceId = is_string($data['device_id']) ? trim($data['device_id']) : $data['device_id'];
        $setorId = isset($data['setor_id']) && is_string($data['setor_id']) ? trim($data['setor_id']) : ($data['setor_id'] ?? null);

        // checagens extras (validation 'exists' cobre a maior parte, mas verificamos antes de gravar)
        $device = Dispositivo::find($deviceId);
        if (! $device) {
            return back()->withErrors(['device_id' => 'Dispositivo informado não encontrado.'])->withInput();
        }

        if ($setorId) {
            $setor = Setor::find($setorId);
            if (! $setor) {
                return back()->withErrors(['setor_id' => 'Setor informado não encontrado.'])->withInput();
            }
        }

        // usar transação para garantir atomicidade
        DB::beginTransaction();
        try {
            if (empty($data['responses'])) {
                return back()->withErrors(['responses' => 'Nenhuma resposta fornecida.'])->withInput();
            }

            foreach ($data['responses'] as $questionId => $response) {
                // sanitize question id (trim) and response
                $questionIdSan = is_string($questionId) ? trim($questionId) : $questionId;

                $question = Pergunta::find($questionIdSan);
                if (! $question) {
                    DB::rollBack();
                    return back()->withErrors(['responses' => "Pergunta com ID '{$questionIdSan}' não encontrada."])->withInput();
                }

                if ($question->resposta_numerica == false) {
                    // texto livre: sanitizar e limitar tamanho
                    $feedback = is_scalar($response) ? trim(strip_tags((string) $response)) : null;

                    if ($feedback === null || trim($feedback) === '') {
                        continue; // pular avaliação sem feedback
                    }

                    if ($feedback !== null) {
                        if (strlen($feedback) > 2000) {     
                            DB::rollBack();
                            return back()->withErrors(['responses' => "Feedback para a pergunta '{$question->texto}' é muito longo."])->withInput();
                        }
                    }
                    $respostaNumerica = null;
                } else {
                    // numérico: garantir que é número entre 0 e 10

                    if ($response === null) {
                        continue; // pular avaliação sem resposta
                    }
                    if (! is_numeric($response)) {
                        DB::rollBack();
                        return back()->withErrors(['responses' => "Resposta para a pergunta '{$question->texto}' deve ser numérica."])->withInput();
                    }

                    $respostaNumerica = (int) $response;
                    if ($respostaNumerica < 0 || $respostaNumerica > 10) {
                        DB::rollBack();
                        return back()->withErrors(['responses' => "Resposta para a pergunta '{$question->texto}' deve ser entre 0 e 10."])->withInput();
                    }
                    $feedback = null;
                }

                Avaliacao::create([
                    'id' => Str::uuid()->toString(),
                    'setor_id' => $setorId,
                    'pergunta_id' => $questionIdSan,
                    'dispositivo_id' => $deviceId,
                    'resposta' => $respostaNumerica,
                    'feedback_textual' => $feedback,
                    'data' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro salvando avaliações: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['general' => 'Erro ao salvar avaliações. Tente novamente mais tarde.' . $e->getMessage(), ['exception' => $e]])->withInput();
        }

        // Sucesso: mostrar página de agradecimento e repassar setor/dispositivo usados
        return view('evaluation.thankyou', [
            'setorId' => $setorId,
            'deviceId' => $deviceId,
        ]);
    }
}
