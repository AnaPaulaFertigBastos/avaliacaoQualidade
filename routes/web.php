<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AdministradorController;

Route::get('/', function () {
    // public evaluation form is the home page
    return app(AvaliacaoController::class)->index();
});

// Submit evaluation
Route::post('/evaluate', [AvaliacaoController::class, 'store'])->name('evaluation.store');

// Admin routes (basic session based)
Route::get('/admin/login', [AdministradorController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdministradorController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdministradorController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [AdministradorController::class, 'dashboard'])->name('admin.dashboard');

// Questions management (basic)
Route::get('/admin/questions', [AdministradorController::class, 'questionsIndex'])->name('admin.questions.index');
Route::get('/admin/questions/create', [AdministradorController::class, 'questionsCreate'])->name('admin.questions.create');
Route::post('/admin/questions', [AdministradorController::class, 'questionsStore'])->name('admin.questions.store');

