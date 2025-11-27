<?php

use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardapioItemController;
use App\Http\Controllers\CompraSugestaoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\FilaProducaoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoItemController;
use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return session()->has('restaurante_id')
        ? redirect()->route('dashboard')
        : redirect()->route('auth.login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login.submit');

// Rotas Admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Usuários
    Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/usuarios/criar', [AdminController::class, 'usuariosCreate'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios', [AdminController::class, 'usuariosStore'])->name('admin.usuarios.store');
    Route::get('/admin/usuarios/{usuario}/editar', [AdminController::class, 'usuariosEdit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{usuario}', [AdminController::class, 'usuariosUpdate'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/{usuario}', [AdminController::class, 'usuariosDestroy'])->name('admin.usuarios.destroy');

    // Restaurantes
    Route::get('/admin/restaurantes', [AdminController::class, 'restaurantes'])->name('admin.restaurantes');
    Route::get('/admin/restaurantes/criar', [AdminController::class, 'restaurantesCreate'])->name('admin.restaurantes.create');
    Route::post('/admin/restaurantes', [AdminController::class, 'restaurantesStore'])->name('admin.restaurantes.store');
    Route::get('/admin/restaurantes/{restaurante}/editar', [AdminController::class, 'restaurantesEdit'])->name('admin.restaurantes.edit');
    Route::put('/admin/restaurantes/{restaurante}', [AdminController::class, 'restaurantesUpdate'])->name('admin.restaurantes.update');
    Route::delete('/admin/restaurantes/{restaurante}', [AdminController::class, 'restaurantesDestroy'])->name('admin.restaurantes.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('restaurante.session')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('insumos', InsumoController::class)->except(['show']);
    Route::resource('cardapio-itens', CardapioItemController::class)
        ->parameters(['cardapio-itens' => 'cardapio_item'])
        ->except(['show']);
    Route::resource('pedidos', PedidoController::class)->except(['show']);
    Route::resource('estoque', EstoqueController::class)->except(['show']);
    Route::resource('alertas', AlertaController::class)->except(['show']);
    Route::resource('compras-sugestoes', CompraSugestaoController::class)->except(['show']);
    Route::resource('receitas', ReceitaController::class)->except(['show']);
    Route::resource('pedido-itens', PedidoItemController::class)->except(['show']);
    Route::resource('fila-producao', FilaProducaoController::class)->except(['show']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
