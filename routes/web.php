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

Route::prefix('admin')->name('admin.')->group(function() {
    // Login público
    Route::get('/login', [AdministradorController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdministradorController::class, 'login'])->name('login.post');

    // Protegidas
    Route::middleware('auth')->group(function() {
        Route::post('/logout', [AdministradorController::class, 'logout'])->name('logout');
        Route::get('/', [AdministradorController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/dados/{setorId?}', [AdministradorController::class, 'dashboardDados'])->name('dashboard.dados');
        Route::get('/questions', [AdministradorController::class, 'questionsIndex'])->name('questions.index');
        Route::get('/questions/create', [AdministradorController::class, 'questionsCreate'])->name('questions.create');
        Route::post('/questions', [AdministradorController::class, 'questionsStore'])->name('questions.store');
        Route::get('/questions/{id}/edit', [AdministradorController::class, 'questionsEdit'])->name('questions.edit');
        Route::put('/questions/{id}', [AdministradorController::class, 'questionsUpdate'])->name('questions.update');
        Route::post('/questions/{id}/up', [AdministradorController::class, 'questionsMoveUp'])->name('questions.up');
        Route::post('/questions/{id}/down', [AdministradorController::class, 'questionsMoveDown'])->name('questions.down');
        Route::get('/respostas-textuais', [AdministradorController::class, 'respostasTextuaisIndex'])->name('respostas.textuais.index');

        // Devices CRUD
        Route::get('/devices', [AdministradorController::class, 'devicesIndex'])->name('devices.index');
        Route::get('/devices/create', [AdministradorController::class, 'devicesCreate'])->name('devices.create');
        Route::post('/devices', [AdministradorController::class, 'devicesStore'])->name('devices.store');
        Route::get('/devices/{id}/edit', [AdministradorController::class, 'devicesEdit'])->name('devices.edit');
        Route::put('/devices/{id}', [AdministradorController::class, 'devicesUpdate'])->name('devices.update');
    });
});

