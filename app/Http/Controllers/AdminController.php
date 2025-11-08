<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Question;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('admin.login');
    }

    // Process login (very basic session auth)
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $data['username'])->first();
        if ($admin && password_verify($data['password'], $admin->password)) {
            session(['admin_id' => $admin->id]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['username' => 'Credenciais inválidas']);
    }

    // Logout
    public function logout()
    {
        session()->forget('admin_id');
        return redirect()->route('admin.login');
    }

    // Dashboard (requires simple session check)
    public function dashboard()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }

        // simple stats: average per question
        $questions = Question::orderBy('order')->get();
        $stats = [];
        foreach ($questions as $q) {
            $avg = Evaluation::where('question_id', $q->id)->avg('response');
            $stats[] = ['question' => $q, 'average' => $avg];
        }

        return view('admin.dashboard', compact('stats'));
    }

    // Basic question management index
    public function questionsIndex()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }
        $questions = Question::orderBy('order')->get();
        return view('admin.questions.index', compact('questions'));
    }

    // Show create form
    public function questionsCreate()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login');
        }
        return view('admin.questions.create');
    }

    // Store question
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

        Question::create([
            'text' => $data['text'],
            'order' => $data['order'] ?? 0,
            'active' => $data['active'] ?? true,
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Pergunta criada');
    }
}
