<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConselhoController;
use App\Http\Controllers\AcompanhamentoController;
use App\Http\Controllers\RelatorioGeralDiscenteController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    //view('welcome');
    return  redirect('/admin'); })->name('login');


Route::get('/conselhos/relatorio', [ConselhoController::class, 'relatorio'])->name('conselhos.pdf');
Route::get('/acompanhamentos/relatorio', [AcompanhamentoController::class, 'relatorio'])->name('acompanhamentos.pdf');
Route::get('/relatorioGeralDiscente/relatorio', [RelatorioGeralDiscenteController::class, 'relatorioGeralDiscente'])->name('relatorioGeralDiscente.pdf');
