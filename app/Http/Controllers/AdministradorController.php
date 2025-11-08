<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use App\Models\Pergunta;
use App\Models\Avaliacao;
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

        $data = $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $data['email'])->first();
        if ($usuario && Hash::check($data['senha'], $usuario->senha)) {
            session(['usuario_id' => $usuario->id]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['username' => 'Credenciais inválidas']);
    }

    public function logout()
    {
        session()->forget('admin_id');
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        if (!session('usuario_id')) {
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
        if (!session('usuario_id')) {
            return redirect()->route('admin.login');
        }
        $questions = Pergunta::all();
        return view('admin.questions.index', compact('questions'));
    }

    public function questionsCreate()
    {
        if (!session('usuario_id')) {
            return redirect()->route('admin.login');
        }
        return view('admin.questions.create');
    }

    public function questionsStore(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        $data = $request->validate([
            'text' => 'required|string',
            'order' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        Pergunta::create([
            'id' => Str::uuid()->toString(),
            'texto' => $data['text'],
            'status' => $data['active'] ?? true,
            'resposta_numerica' => true,
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Pergunta criada');
    }
}
