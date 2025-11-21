<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pergunta;
use App\Models\Avaliacao;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdministradorController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $questions = Pergunta::orderBy('ordem')->get();
        $setores = \App\Models\Setor::all();
        $stats = [];
        foreach ($questions as $q) {
            $avg = Avaliacao::where('pergunta_id', $q->id)->avg('resposta');
            $stats[] = ['question' => $q, 'average' => $avg];
        }
        // Dados para gráfico de média por pergunta (apenas perguntas numéricas)
        $statsNumericas = array_values(array_filter($stats, fn($s) => $s['question']->resposta_numerica));
        $chartLabels = array_values(array_map(fn($s) => (string)$s['question']->texto, $statsNumericas));
        $chartAverages = array_values(array_map(fn($s) => $s['average'] !== null ? (float)round($s['average'], 2) : 0.0, $statsNumericas));

        // Distribuição de pontuações (0-10) geral
        $scoreCounts = Avaliacao::select('resposta')
            ->whereNotNull('resposta')
            ->get()
            ->groupBy('resposta')
            ->map(fn($grp) => $grp->count());
        $scores = range(0,10);
        $scoreDistribution = array_map(fn($v) => $scoreCounts->get($v, 0), $scores);

        // Garantir arrays indexados simples
        $scores = array_values(array_map('intval', $scores));
        $scoreDistribution = array_values(array_map('intval', $scoreDistribution));

        // Amostras de respostas textuais (máximo 3 mais recentes)
        $textualSamples = Avaliacao::with(['pergunta'])
            ->whereNotNull('feedback_textual')
            ->orderBy('data', 'desc')
            ->limit(3)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'chartLabels' => $chartLabels,
            'chartAverages' => $chartAverages,
            'scoreDistributionLabels' => $scores,
            'scoreDistributionValues' => $scoreDistribution,
            'setores' => $setores,
            'textualSamples' => $textualSamples,
        ]);
    }

    public function dashboardDados(?string $setorId = null)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Não autenticado'], 401);
        }

        // Perguntas numéricas
        $perguntas = Pergunta::where('resposta_numerica', true)->get();
        $labels = [];
        $averages = [];
        foreach ($perguntas as $p) {
            $query = Avaliacao::where('pergunta_id', $p->id);
            if ($setorId && \Illuminate\Support\Str::isUuid($setorId)) {
                $query->where('setor_id', $setorId);
            }
            $avg = $query->avg('resposta');
            $labels[] = (string)$p->texto;
            $averages[] = $avg !== null ? (float)round($avg, 2) : 0.0;
        }

        // Distribuição de pontuações geral (ou por setor se definido)
        $distQuery = Avaliacao::select('resposta')->whereNotNull('resposta');
        if ($setorId && \Illuminate\Support\Str::isUuid($setorId)) {
            $distQuery->where('setor_id', $setorId);
        }
        $scoreCounts = $distQuery->get()->groupBy('resposta')->map(fn($grp) => $grp->count());
        $scores = range(0,10);
        $distribution = array_map(fn($v) => (int)$scoreCounts->get($v, 0), $scores);

        return response()->json([
            'labels' => $labels,
            'averages' => $averages,
            'scoreLabels' => $scores,
            'scoreValues' => $distribution,
        ]);
    }

    public function questionsIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $questions = Pergunta::orderBy('ordem')->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function questionsCreate()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        return view('admin.questions.create');
    }

    public function questionsStore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate([
            'texto' => 'required|string',
            'status' => 'nullable|boolean',
            'resposta_numerica' => 'nullable|boolean',
        ]);

        $nextOrder = (int) Pergunta::max('ordem') + 1;
        Pergunta::create([
            'id' => Str::uuid()->toString(),
            'texto' => $data['texto'],
            'status' => (bool)($data['status'] ?? true),
            'resposta_numerica' => (bool)($data['resposta_numerica'] ?? true),
            'ordem' => $nextOrder,
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Pergunta criada');
    }

    public function questionsEdit(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $pergunta = Pergunta::findOrFail($id);
        return view('admin.questions.edit', compact('pergunta'));
    }

    public function questionsUpdate(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $pergunta = Pergunta::findOrFail($id);

        $data = $request->validate([
            'texto' => 'required|string',
            'status' => 'nullable|boolean',
            'resposta_numerica' => 'nullable|boolean',
        ]);

        $pergunta->update([
            'texto' => $data['texto'],
            'status' => (bool)($data['status'] ?? false),
            'resposta_numerica' => (bool)($data['resposta_numerica'] ?? false),
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Pergunta atualizada');
    }

    // Dispositivos CRUD
    public function devicesIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $devices = Dispositivo::all();
        return view('admin.devices.index', compact('devices'));
    }

    // Listagem de setores para o admin (id, descricao, ativo)
    public function setoresIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $setores = \App\Models\Setor::orderBy('descricao')->get();
        return view('admin.setores.index', compact('setores'));
    }

    public function devicesCreate()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        return view('admin.devices.create');
    }

    public function devicesStore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);
        Dispositivo::create([
            'id' => Str::uuid()->toString(),
            'nome' => $data['nome'],
            'status' => (bool)($data['status'] ?? true),
        ]);
        return redirect()->route('admin.devices.index')->with('success', 'Dispositivo criado');
    }

    public function devicesEdit(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $device = Dispositivo::findOrFail($id);
        return view('admin.devices.edit', compact('device'));
    }

    public function devicesUpdate(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $device = Dispositivo::findOrFail($id);
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);
        $device->update([
            'nome' => $data['nome'],
            'status' => (bool)($data['status'] ?? false),
        ]);
        return redirect()->route('admin.devices.index')->with('success', 'Dispositivo atualizado');
    }

    // Listagem completa de respostas textuais de perguntas não numéricas
    public function respostasTextuaisIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $avaliacoesTextuais = Avaliacao::with(['pergunta','setor','dispositivo'])
            ->whereNotNull('feedback_textual')
            ->whereHas('pergunta', fn($q) => $q->where('resposta_numerica', false))
            ->orderBy('data','desc')
            ->paginate(25);

        return view('admin.questions.textual_respostas', [
            'avaliacoesTextuais' => $avaliacoesTextuais,
        ]);
    }

    public function questionsMoveUp(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $current = Pergunta::findOrFail($id);
        $above = Pergunta::where('ordem', '<', $current->ordem)->orderBy('ordem', 'desc')->first();
        if (!$above) {
            return redirect()->route('admin.questions.index')->with('success', 'Já está no topo');
        }
        \DB::transaction(function() use ($current, $above) {
            $tmp = $current->ordem;
            $current->update(['ordem' => $above->ordem]);
            $above->update(['ordem' => $tmp]);
        });
        return redirect()->route('admin.questions.index')->with('success', 'Ordem atualizada');
    }

    public function questionsMoveDown(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $current = Pergunta::findOrFail($id);
        $below = Pergunta::where('ordem', '>', $current->ordem)->orderBy('ordem', 'asc')->first();
        if (!$below) {
            return redirect()->route('admin.questions.index')->with('success', 'Já está na última posição');
        }
        \DB::transaction(function() use ($current, $below) {
            $tmp = $current->ordem;
            $current->update(['ordem' => $below->ordem]);
            $below->update(['ordem' => $tmp]);
        });
        return redirect()->route('admin.questions.index')->with('success', 'Ordem atualizada');
    }
}
