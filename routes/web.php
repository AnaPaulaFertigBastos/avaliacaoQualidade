<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AdministradorController;

Route::get('/', function () {
    // public evaluation form is the home page
    return app(AvaliacaoController::class)->index();
});

// route that accepts setor and dispositivo ids (UUIDs) — both optional
// allows: /avaliacao, /avaliacao/{setorId}, /avaliacao/{setorId}/{dispositivoId}
Route::get('/avaliacao/{setorId?}/{dispositivoId?}', [AvaliacaoController::class, 'index'])->name('avaliacao.form');

// endpoint JSON de perguntas usado por AJAX
Route::get('/avaliacao/questions/{setorId?}/{dispositivoId?}', [AvaliacaoController::class, 'perguntasJson'])->name('avaliacao.perguntas');

// Enviar avaliação
Route::post('/evaluate', [AvaliacaoController::class, 'salvar'])->name('avaliacao.salvar');

// Admin routes (basic session based)
Route::get('/admin/login', [AdministradorController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdministradorController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdministradorController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [AdministradorController::class, 'dashboard'])->name('admin.dashboard');

// Questions management (basic)
Route::get('/admin/questions', [AdministradorController::class, 'questionsIndex'])->name('admin.questions.index');
Route::get('/admin/questions/create', [AdministradorController::class, 'questionsCreate'])->name('admin.questions.create');
Route::post('/admin/questions', [AdministradorController::class, 'questionsStore'])->name('admin.questions.store');

