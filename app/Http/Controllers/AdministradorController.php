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

        $questions = Pergunta::all();
        $stats = [];
        foreach ($questions as $q) {
            $avg = Avaliacao::where('pergunta_id', $q->id)->avg('resposta');
            $stats[] = ['question' => $q, 'average' => $avg];
        }

        return view('admin.dashboard', compact('stats'));
    }

    public function questionsIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $questions = Pergunta::all();
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

        Pergunta::create([
            'id' => Str::uuid()->toString(),
            'texto' => $data['texto'],
            'status' => (bool)($data['status'] ?? true),
            'resposta_numerica' => (bool)($data['resposta_numerica'] ?? true),
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

    public function questionsDestroy(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $pergunta = Pergunta::findOrFail($id);
        $pergunta->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Pergunta excluída');
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

    public function devicesDestroy(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        $device = Dispositivo::findOrFail($id);
        $device->delete();
        return redirect()->route('admin.devices.index')->with('success', 'Dispositivo excluído');
    }
}
